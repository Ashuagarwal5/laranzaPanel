<?php namespace App\Services;

/**
 * A plain-file WhatsApp log (storage/logs/whatsapp.log), independent of the
 * app's log config so it always works. It records every step an event takes
 * on the way to WhatsApp - fired, message found, WhatsApp enabled, template
 * mapped, sent or failed - so a message that "did not trigger" shows exactly
 * where it stopped.
 *
 * Also knows the other log files the admin Logs page can show and clear.
 */
class WhatsappLog
{
	// Above this the file is cut back to its newest half, so it can't grow forever.
	const MAX_BYTES = 5242880;

	const ENABLED = false;

	/**
	 * key => [label, file name] of every log the admin page manages.
	 */
	public static function files()
	{
		return [
			'whatsapp' => ['WhatsApp Log', 'whatsapp.log'],
			'webhook'  => ['Webhook Log', 'whatsapp-webhook.log'],
			'laravel'  => ['Laravel Log', 'laravel.log'],
		];
	}

	public static function path($key)
	{
		$files = self::files();
		return isset($files[$key]) ? storage_path('logs/'.$files[$key][1]) : null;
	}

	/**
	 * Append one line. Never throws - logging must not break a send.
	 */
	public static function write($level, $event, $message, array $context = array())
	{
		// The step-by-step file log is switched off (there is no page for it any
		// more). Set ENABLED to true to start writing storage/logs/whatsapp.log again.
		if (!self::ENABLED) {
			return;
		}

		try {
			$path = self::path('whatsapp');
			if (is_file($path) && filesize($path) > self::MAX_BYTES) {
				$keep = substr((string) file_get_contents($path), -(int) (self::MAX_BYTES / 2));
				@file_put_contents($path, $keep, LOCK_EX);
			}
			$line = '['.date('Y-m-d H:i:s').'] '.strtoupper($level).' ['.$event.'] '.$message;
			if (!empty($context)) {
				$line .= ' '.json_encode($context, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
			}
			@file_put_contents($path, $line.PHP_EOL, FILE_APPEND | LOCK_EX);
		} catch (\Throwable $e) {
			// ignore
		}
	}

	/**
	 * The last $lines lines of a log, newest first.
	 */
	public static function tail($key, $lines = 300)
	{
		$path = self::path($key);
		if (!$path || !is_file($path)) {
			return [];
		}
		// Read only the end of the file - laravel.log can be huge.
		$size   = filesize($path);
		$offset = max(0, $size - 512 * 1024);
		$handle = fopen($path, 'rb');
		if (!$handle) {
			return [];
		}
		fseek($handle, $offset);
		$chunk = stream_get_contents($handle);
		fclose($handle);

		$all = preg_split('/\r?\n/', rtrim((string) $chunk));
		if ($offset > 0) {
			array_shift($all); // first line is probably cut in half
		}
		return array_reverse(array_slice($all, -$lines));
	}

	public static function size($key)
	{
		$path = self::path($key);
		return ($path && is_file($path)) ? filesize($path) : 0;
	}

	/**
	 * Empty a log file (kept in place, so file permissions stay as they are).
	 */
	public static function clear($key)
	{
		$path = self::path($key);
		if (!$path) {
			return false;
		}
		return @file_put_contents($path, '', LOCK_EX) !== false;
	}
}
