# Messaging: SMS, WhatsApp and App Notification

How automatic messages work, and where to change them. SMS and WhatsApp both go
through **Celitix** (same base URL, same API key).

---

## 1. The idea in one paragraph

Every message belongs to one **event** — a moment the code fires, such as
"a redemption was approved". Under **Messages ➜ Add Message** the admin picks the
event once, then fills in any of three tabs: **SMS** (DLT template text),
**WhatsApp** (an approved Celitix template) and **App Notification** (plain text).
Each tab has its own *enable* switch. When the event happens in the app, every
enabled channel goes out on its own.

```
Admin: Messages ➜ Add Message                         Runtime
─────────────────────────────                         ───────
Event (one per message)                       admin approves a claim
 ├─ SMS tab       ➜ messages_info.sms_*                     │
 ├─ WhatsApp tab  ➜ whatsapp_configurations                 ▼
 └─ App tab       ➜ messages_info.app_message   SendMessage::getSendMessage()
                                                  ├─ CelitixSms::send()          ➜ sms_message_logs
                                                  ├─ CelitixWhatsapp::sendTemplate() ➜ whatsapp_message_logs
                                                  └─ notifications row (Pending) ➜ Nofification:cron pushes it
```

---

## 2. Files

| File | Responsibility |
|---|---|
| `app/SendMessage.php` | The dispatcher. `getSendMessage()` is the entry point every trigger calls; it runs each enabled channel and never throws |
| `app/Services/MessageEvents.php` | **The event list.** Every event the code fires, with its description (drives the dropdown) |
| `app/Services/MessageTokens.php` | **The variable registry.** Every `{token}`, its label, and where its value comes from — shared by all channels |
| `app/Services/CelitixSms.php` | SMS: `{#var#}` detection, rendering, the HTTP call, logging |
| `app/Services/CelitixWhatsapp.php` | WhatsApp: template sync, sending, error mapping, number formatting |
| `app/Message.php` | One event's message (`messages_info`). `modes()`, `smsMapping()`, `whatsappConfiguration()` |
| `app/WhatsappConfiguration.php` / `app/WhatsappTemplate.php` | Event ➜ template mapping / synced template |
| `app/Http/Controllers/Admin/MessageController.php` | Admin screens: list, tabbed create/edit, view + test send, WhatsApp template ajax & sync |
| `resources/views/admin/message/` | `list`, `create` (tabs), `show` |
| `config/whatsapp.php` | `WP_BASE_URL`, `WP_ENDPOINT`, `SMS_ENDPOINT` |

---

## 3. Tables

| Table | Holds |
|---|---|
| `messages_info` | `title` (the event), `mode` (enabled channels: `Sms,WhatsApp,AppNotification`), `sms_template_id`, `sms_sender_id`, `sms_message`, `sms_variable_mapping`, `app_message` |
| `whatsapp_configurations` | `message_id`, `template_name`, `language`, `category`, `variable_mapping`, `status` (Active while the WhatsApp tab is enabled) |
| `whatsapp_templates` | Mirror of the Celitix template list. Rewritten by every sync |
| `sms_message_logs` | One row per SMS attempt: `request` (API key masked), `response`, `status`, `provider_message_id`, `error` |
| `whatsapp_message_logs` | One row per WhatsApp attempt |
| `notifications` | App notifications; the app's notification list reads this, the cron pushes `Pending` rows |
| `website_settings` | `whatsaap_api_key` (Celitix key, SMS + WhatsApp), `waba_number`, `sms_entity_id` |

Mappings are **placeholder ➜ token**:

```json
sms_variable_mapping:  {"1":"{user_name}","2":"{points}"}          // nth {#var#} ➜ token
variable_mapping:      {"user_name":"{user_name}","1":"{points}"}  // template placeholder ➜ token
```

A value that is not a known token is fixed text the admin typed.

---

## 4. SMS

Request (GET, everything in the query string):

```
{WP_BASE_URL}/v1/sms/send/?message=...&mobile=91XXXXXXXXXX&senderid=...&entityid=...&tempid=...&apikey=...
```

- `message` is the DLT text with each `{#var#}` replaced, in order, by the mapped values.
- Variables may be written `{#var#}`, `{{#var1}}` or `{{#var1#}}`; they are numbered by position.
- Success is `data[0].code == "000"` (status `QUEUED`). Anything else is logged as `failed` with the reason.
- Nothing is sent (and a `failed` log is written) when the API key or Entity ID is missing,
  the number is invalid, or the Sender ID / Template ID / text is empty.

## 5. App Notification

The text may contain tokens written directly, e.g. `Dear {user_name}`. On send a
`notifications` row is inserted (`title` = event, `status` = Pending). Events sent
to the admin (`Admin Registration Notification`) skip this channel.

---

## 6. How to add a new variable

Two edits, both in `app/Services/MessageTokens.php`: declare it in `groups()`,
then say where its value comes from in `resolve()`. Every tab's dropdown and the
send path pick it up.

If the value is not on the customer or the claim, pass it from the trigger:

```php
SendMessage::getSendMessage('Redemption Cancelled', $userId, $points, 'user', [
    'claim'  => $claim,
    'values' => ['{cancelled_by}' => $admin->full_name],
]);
```

## 6a. Recipients (Send To)

Each message has a `recipient` (`messages_info.recipient`): `user` (the
carpenter/customer the event is about), `admin` (`website_settings.admin_mobile_no`)
or `dealer` (the claim's `dealer_mobile`, else the customer's dealer). One event
can have **one message per recipient**, each with its own SMS/WhatsApp/App
setup - e.g. *Redemption Request Received* ➜ Admin template now, and a
Carpenter template later. `getSendMessage()` sends every one of them. The
variables always describe the customer the event is about, whoever receives
it. App notifications go to the carpenter or dealer; never to the admin.

`Redemption Request Received` fires once per app request (a basket of several
products is one request). Use `{request_id}`, `{request_products}` and
`{request_total_points}` for the whole request.

## 7. How to add a new event

1. Fire it where it happens:
   `SendMessage::getSendMessage('Redemption Delivered', $claim->user_id, $claim->points_spent, 'user', ['claim' => $claim]);`
2. Add `'Redemption Delivered' => 'Claim delivered to the customer'` to `MessageEvents::groups()`.
3. Configure it under Messages ➜ Add Message.

The `title` string must match exactly; a message whose title is not in
`MessageEvents` shows *not fired by app* in the list and never sends.

---

## 8. Dealer pickup bot (inbound WhatsApp)

When the admin marks a `reward_claims` row **Dispatched** (the product has
physically reached the dealer - see `RewardClaimController::dispatchToDealer()`),
`RewardClaim::generateRedemptionCode()` stamps a 6-digit `redemption_code`
(+ `code_generated_at`) on it. Not on approval - a claim only gets a code once
it is actually sitting at the dealer's counter. The carpenter sees the code in
the app (`claimHistory`, only while status is `Dispatched`) and reads it out
to the dealer in person.

```
dealer WhatsApp                                        backend
────────────────                                        ───────
"start"                         ──POST /api/whatsapp/webhook──▶ WhatsappWebhookController::handle()
                                                                   └─ value.messages[] ➜ WhatsappBotService::handleIncoming()
◀── "You have N pending pickup(s). Send the                    ┤   ├─ Dealer::checkDealerByMobileNumber() (is this number a dealer? silently ignored if not)
    carpenter's mobile number." ───────────────────────────────┤   └─ handleStart(): count Dispatched claims for this dealer with a code, unverified
                                                                 │        (creates whatsapp_bot_sessions row: state=awaiting_carpenter_mobile)
"9876543210" (carpenter's mobile)                                ▶
                                                                 │   handleCarpenterMobile(): carpenter = users.mobileno + dealer_id match
◀── "Ashu has the following pending pickup(s):                 ┤        active claims = Dispatched, dealer_id, user_id=carpenter, code set, unverified
    - Hand Shower                                              │        (session -> state=awaiting_code, carpenter_user_id=<id>)
    - Drawer x2
    Please send me the 6-digit code." ────────────────────────┘
"483920" (code from carpenter)                                    ▶
                                                                 │   handleCode(): match claim by dealer_id + carpenter_user_id (from session) + code
◀── "Code verified! Please give <product> ..." ─────────────────┘        ➜ RewardClaim::markDelivered(..., true) (fires 'Redemption Delivered', sets code_verified_at)
```

- **Stateful, three steps**: `start` → carpenter's mobile → code. Which
  carpenter was picked has to survive between messages (the final code has to
  be checked against *that* carpenter's claim, not just any of the dealer's),
  so `whatsapp_bot_sessions` (keyed by the dealer's number) tracks the stage.
  The row is deleted once a code is verified, or once `start` finds nothing
  pending.
- **Silent unless verified**: a number that isn't a registered dealer gets no
  reply at all - not even a "you're not registered" message.
- **Dealer identity**: matched via `Dealer::checkDealerByMobileNumber()`
  (`dealers.mobile_no`, plain 10-digit) against the last 10 digits of the
  inbound sender.
- **Carpenter identity**: `users.mobileno` + `users.dealer_id` matching the
  dealer's user id - i.e. the carpenter must be registered *under this
  dealer* to be found.
- **Assumed payload shape**: `WhatsappWebhookController::handle()` parses
  inbound messages the same way it already parses Meta-format delivery
  statuses (`entry[].changes[].value.messages[]`), because
  `CelitixWhatsapp::sendTemplate()`'s payload is already a 1:1 mirror of
  Meta's Cloud API. Every webhook hit is appended raw to
  `storage/logs/whatsapp-webhook.log` (not `laravel.log`, so it's easy to
  `tail`/`Get-Content -Wait`) to confirm or correct this against real traffic.
- The real webhook URL Celitix must be configured with is
  `/api/whatsapp/webhook` (routes/api.php is auto-prefixed with `api`).
- Replies use `CelitixWhatsapp::sendText()` (free-text/session message, not a
  template) - only deliverable inside the 24h window opened by the user's own
  message.
- There is no manual "mark delivered" admin action - `Dispatched -> Delivered`
  only happens via a verified code, so a delivery can't be recorded without
  the carpenter actually having produced the code from the dealer.

## 9. Troubleshooting

| Symptom | Check |
|---|---|
| Nothing sends | Is the channel's tab **enabled** for that event? Is the event in `MessageEvents` (fired by code)? |
| SMS fails | `sms_message_logs.error`. Common: Entity ID not set, Template ID/Sender ID not matching DLT, text not matching the approved template |
| WhatsApp template dropdown empty | **Sync Templates** in the WhatsApp tab. Only `APPROVED` templates are listed |
| WhatsApp fails | `whatsapp_message_logs.error`. `401` = bad API key, `403` = WABA number not allowed |
| Variable arrives blank | The token has no value for that event (e.g. `{tracking_number}` before dispatch) |
| App notification in list but no push | The cron runs `Nofification:cron`; the push itself uses `App\PushNotification` (legacy FCM) |

Test sends: open a message with **View** ➜ *Send Test Message*.

```sql
SELECT id, mobileno, status, error, created_at FROM tbl_sms_message_logs ORDER BY id DESC LIMIT 20;
```
