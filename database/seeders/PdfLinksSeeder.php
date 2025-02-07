<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PdfLinksSeeder extends Seeder
{
    public function run()
    {
        $urls = [
            ['url' => 'https://www.aami.com.au/policy-documents/personal/comprehensive-car-insurance.html'],
            ['url' => 'https://www.aami.com.au/business-insurance.html?intCMP=AMI:GI:PI:BIN:OSL:20200301:0328'],
            ['url' => 'https://online.aami.com.au/macproperty/aami/#/'],
            ['url' => 'http://www.bom.gov.au/inside/index.shtml?ref=hdr'],
        ];

        DB::table('pdf_links')->insert($urls);
    }
}
