<?php

namespace App\Jobs;

// use App\Libraries\Webhook;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Process;
use App\History;
use App\Products;
use App\Category;
use Throwable;
use Storage;
use ZipArchive;
use File;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
use App\Import\ProductsImport;

class QrCodeGeneratorJobs implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @var string $hook
     */
    public $data;
    public $history;

    /**
     * Create a new job instance.
     *
     *
     * @return void
     */
    public function __construct(History $history)
    {
        $this->history = $history;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
                $id = $this->history->update_data_id;
                $history_id = $this->history->id;
                $bulk = $this->history->bulk_qr_code;
                $category_id = $this->history->category_id;
                $reward_points = $this->history->reward_points;
                $remark = $this->history->remark;
                $size = $this->history->qr_size;
                if ($id != null) {
                    $data_array = array();
                    $check = History::where('id', $id)->first();
                    $qr_code_id = explode(',', $check->qr_code_id);
                    $used_status_no_data = Products::whereIn('id', $qr_code_id)->where('used_status', 'No')->get();
                    foreach ($used_status_no_data as $key => $value) {
                       $update = Products::where('id',$value->id)->first();
                       $update->reward_points = $reward_points;
                       $update->save();
                    }
                    $history = History::where('id', $history_id)->first();
                    $history->status = "Completed";
                    $history->save();
                } 
                else
                {
                    $year = date('Y');
                    $month = date('m');
                    $week = date('W');
                    $folder = $year.'/'.$month.'/'.$week;
                    // @mkdir($folder,0777,true);
                    // @chmod($folder,0777);
                    Storage::disk('uploads')->makeDirectory($folder);

                    $data_array = array();
                    for ($i=0; $i < $bulk; $i++) {
                        $qrcode = Category::where('id', $category_id)->first()->category_code;
                        $qucode_name = strtoupper($qrcode);
                        $qrcode_no = $qucode_name.$reward_points.$i.strtoupper(Str::random(10));
                        $pro = new Products();
                        $save_path  = storage_path('app/uploads/'.$folder.'/'.$qrcode_no.'.png');
                        $this->generate_qrcode($qrcode_no, $save_path, $size);
                        $pro->qr_value = $qrcode_no;
                        $pro->qrcode_no = $qrcode_no;
                        $pro->size = $size;
                        $pro->reward_points = $reward_points;
                        $pro->category_id = $category_id;
                        $pro->total_no = $bulk;
                        $pro->folder_name = $folder;
                        $pro->save();
                        $data_array[] = $pro->id;
                    }
                    $id_data = implode(',', $data_array);
                    $history = History::where('id', $history_id)->first();
                    $history->qr_code_id = $id_data;
                    $history->status = "Completed";
                    $history->save();
                }
        } catch (Throwable $th) {
            echo $th;
            $this->history->errorlog=$th;
            $this->history->ended("PHP Error");
            $this->history->save();
        }
    }
    public function isJson($string)
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
    private function generate_qrcode($data, $save_path, $size): void
    {
        try {
            $result = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([])
            ->data($data)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size($size)
            ->margin(10)
            ->roundBlockSizeMode(new RoundBlockSizeModeMargin())
            ->build();
            // Save it to a file
            $result->saveToFile($save_path);
        } catch (Throwable $th) {
            $this->history->errorlog=$th;
            $this->history->ended("PHP Error");
            $this->history->save();
        }
    }
}
