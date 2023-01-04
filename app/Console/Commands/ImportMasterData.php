<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Customer;
use App\Models\Product;
use Carbon\Carbon;

class ImportMasterData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import master data';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $records = 0;
        $this->info('please wait, while importing customer data...');
        if (($open = fopen(storage_path() . "/customers.csv", "r")) !== FALSE) {

            while (($data = fgetcsv($open, 1000, ",")) !== FALSE) {
                $records++;

                if ($records == 1) {
                    continue;
                } else {
                    $separated_date = explode(',', $data[4]);

                    $date =  Carbon::parse($separated_date[1] . "," . $separated_date[2])->format('Y-m-d');

                    Customer::create([
                        'job_title' => $data[1],
                        'email' => $data[2],
                        'name' => $data[3],
                        'registered_since' => $date,
                        'phone' => $data[5],
                    ]);
                }
            }

            fclose($open);
        }


        $this->info($records - 1 . ' Customers Imported Successfully');


        $records = 0;
        $this->info('please wait, while importing product data...');
        if (($open = fopen(storage_path() . "/products.csv", "r")) !== FALSE) {

            while (($data = fgetcsv($open, 1000, ",")) !== FALSE) {
                $records++;

                if ($records == 1) {
                    continue;
                } else {
                    Product::create([
                        'product_name' => $data[1],
                        'price' => $data[2],
                    ]);
                }
            }

            fclose($open);
        }


        $this->info($records - 1 . ' Products Imported Successfully');
        return Command::SUCCESS;
    }
}
