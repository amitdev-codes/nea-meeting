<?php

namespace Modules\Master\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Master\Models\Status;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['code' => 2001, 'name' => 'Pending', 'name_np' => 'विचाराधीन','status'=>true],
            ['code' => 2002, 'name' => 'Submitted', 'name_np' => 'पेश गरिएको','status'=>true],
            ['code' => 2003,  'name' => 'Under Review', 'name_np' => 'समीक्षाधीन','status'=>true],
            ['code' => 2004, 'name' => 'Assigned', 'name_np' => 'तोकेको','status'=>true],
            ['code' => 2005, 'name' => 'Resolved', 'name_np' => 'समाधान भएको','status'=>true],
            ['code' => 2007, 'name' => 'Rejected', 'name_np' => 'अस्वीकृत','status'=>true],
        ];

        foreach ($statuses as $status) {
            Status::create($status);
        }
    }
}
