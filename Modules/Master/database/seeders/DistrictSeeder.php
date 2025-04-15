<?php

namespace Modules\Master\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Master\Models\District;
use Modules\Master\Models\Province;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $districts = [
            // Province 1 (2001)
            ['code' => '2019', 'province_code' => '2001', 'name' => 'Jhapa', 'name_np' => 'झापा', 'status' => false],
            ['code' => '2021', 'province_code' => '2001', 'name' => 'Sunsari', 'name_np' => 'सुनसरी', 'status' => false],
            ['code' => '2012', 'province_code' => '2001', 'name' => 'Terhathum', 'name_np' => 'तेह्रथुम', 'status' => false],
            ['code' => '2013', 'province_code' => '2001', 'name' => 'Dhankuta', 'name_np' => 'धनकुटा', 'status' => false],
            ['code' => '2014', 'province_code' => '2001', 'name' => 'Bhojpur', 'name_np' => 'भोजपुर', 'status' => false],
            ['code' => '2015', 'province_code' => '2001', 'name' => 'Khotang', 'name_np' => 'खोटाङ', 'status' => false],
            ['code' => '2016', 'province_code' => '2001', 'name' => 'Solukhumbu', 'name_np' => 'सोलुखुम्बु', 'status' => false],
            ['code' => '2017', 'province_code' => '2001', 'name' => 'Okhaldhunga', 'name_np' => 'ओखलढुङ्गा', 'status' => false],
            ['code' => '2018', 'province_code' => '2001', 'name' => 'Udayapur', 'name_np' => 'उदयपुर', 'status' => false],
            ['code' => '2020', 'province_code' => '2001', 'name' => 'Morang', 'name_np' => 'मोरङ', 'status' => false],
            ['code' => '2008', 'province_code' => '2001', 'name' => 'Taplejung', 'name_np' => 'ताप्लेजुङ्ग', 'status' => false],
            ['code' => '2009', 'province_code' => '2001', 'name' => 'Panchthar', 'name_np' => 'पाँचथर', 'status' => false],
            ['code' => '2010', 'province_code' => '2001', 'name' => 'Ilam', 'name_np' => 'इलाम', 'status' => false],
            ['code' => '2011', 'province_code' => '2001', 'name' => 'Sankhuwasabha', 'name_np' => 'संखुवासभा', 'status' => false],
        
            // Province 2 (2002)
            ['code' => '2028', 'province_code' => '2002', 'name' => 'Bara', 'name_np' => 'बारा', 'status' => false],
            ['code' => '2027', 'province_code' => '2002', 'name' => 'Rautahat', 'name_np' => 'रौतहट', 'status' => false],
            ['code' => '2029', 'province_code' => '2002', 'name' => 'Parsa', 'name_np' => 'पर्सा', 'status' => false],
            ['code' => '2026', 'province_code' => '2002', 'name' => 'Sarlahi', 'name_np' => 'सर्लाही', 'status' => false],
            ['code' => '2025', 'province_code' => '2002', 'name' => 'Mahottari', 'name_np' => 'महोत्तरी', 'status' => true],
            ['code' => '2024', 'province_code' => '2002', 'name' => 'Dhanusha', 'name_np' => 'धनुषा', 'status' => true],
            ['code' => '2023', 'province_code' => '2002', 'name' => 'Siraha', 'name_np' => 'सिराहा', 'status' => true],
            ['code' => '2022', 'province_code' => '2002', 'name' => 'Saptari', 'name_np' => 'सप्तरी', 'status' => true],
        
            // Province 3 (2003)
            ['code' => '2033', 'province_code' => '2003', 'name' => 'Kavrepalanchok', 'name_np' => 'काभ्रेपलाञ्चोक', 'status' => false],
            ['code' => '2042', 'province_code' => '2003', 'name' => 'Kathmandu', 'name_np' => 'काठमाडौं', 'status' => false],
            ['code' => '2030', 'province_code' => '2003', 'name' => 'Dolakha', 'name_np' => 'दोलखा', 'status' => true],
            ['code' => '2031', 'province_code' => '2003', 'name' => 'Ramechhap', 'name_np' => 'रामेछाप', 'status' => false],
            ['code' => '2032', 'province_code' => '2003', 'name' => 'Sindhuli', 'name_np' => 'सिन्धुली', 'status' => false],
            ['code' => '2034', 'province_code' => '2003', 'name' => 'Sindhupalchok', 'name_np' => 'सिन्धुपाल्चोक', 'status' => true],
            ['code' => '2035', 'province_code' => '2003', 'name' => 'Rasuwa', 'name_np' => 'रसुवा', 'status' => false],
            ['code' => '2036', 'province_code' => '2003', 'name' => 'Nuwakot', 'name_np' => 'नुवाकोट', 'status' => false],
            ['code' => '2037', 'province_code' => '2003', 'name' => 'Dhading', 'name_np' => 'धादिङ', 'status' => true],
            ['code' => '2038', 'province_code' => '2003', 'name' => 'Chitwan', 'name_np' => 'चितवन', 'status' => false],
            ['code' => '2039', 'province_code' => '2003', 'name' => 'Makawanpur', 'name_np' => 'मकवानपुर', 'status' => false],
            ['code' => '2040', 'province_code' => '2003', 'name' => 'Bhaktapur', 'name_np' => 'भक्तपुर', 'status' => false],
            ['code' => '2041', 'province_code' => '2003', 'name' => 'Lalitpur', 'name_np' => 'ललितपुर', 'status' => false],
        
            // Province 4 (2004)
            ['code' => '2048', 'province_code' => '2004', 'name' => 'Mustang', 'name_np' => 'मुस्ताङ', 'status' => false],
            ['code' => '2049', 'province_code' => '2004', 'name' => 'Parbat', 'name_np' => 'पर्वत', 'status' => false],
            ['code' => '2050', 'province_code' => '2004', 'name' => 'Syangja', 'name_np' => 'स्याङजा', 'status' => false],
            ['code' => '2053', 'province_code' => '2004', 'name' => 'Nawalparasi (Eastern Part from Bardaghat Susta)', 'name_np' => 'नवलपरासी (बर्दघाट सुस्ता पूर्व)', 'status' => false],
            ['code' => '2051', 'province_code' => '2004', 'name' => 'Myagdi', 'name_np' => 'म्याग्दी', 'status' => false],
            ['code' => '2052', 'province_code' => '2004', 'name' => 'Baglung', 'name_np' => 'बाग्लुङ', 'status' => false],
            ['code' => '2047', 'province_code' => '2004', 'name' => 'Manang', 'name_np' => 'मनाङ', 'status' => false],
            ['code' => '2046', 'province_code' => '2004', 'name' => 'Kaski', 'name_np' => 'कास्की', 'status' => false],
            ['code' => '2045', 'province_code' => '2004', 'name' => 'Tanahun', 'name_np' => 'तनहुँ', 'status' => false],
            ['code' => '2043', 'province_code' => '2004', 'name' => 'Gorkha', 'name_np' => 'गोरखा', 'status' => true],
            ['code' => '2044', 'province_code' => '2004', 'name' => 'Lamjung', 'name_np' => 'लमजुङ', 'status' => false],
        
            // Province 5 (2005)
            ['code' => '2057', 'province_code' => '2005', 'name' => 'Palpa', 'name_np' => 'पाल्पा', 'status' => false],
            ['code' => '2058', 'province_code' => '2005', 'name' => 'Arghakhanchi', 'name_np' => 'अर्घाखाँची', 'status' => false],
            ['code' => '2059', 'province_code' => '2005', 'name' => 'Gulmi', 'name_np' => 'गुल्मी', 'status' => false],
            ['code' => '2054', 'province_code' => '2005', 'name' => 'Nawalparasi (Western Part from Bardaghat Susta)', 'name_np' => 'नवलपरासी (बर्दघाट सुस्ता पश्चिम)', 'status' => false],
            ['code' => '2055', 'province_code' => '2005', 'name' => 'Rupandehi', 'name_np' => 'रूपन्देही', 'status' => false],
            ['code' => '2056', 'province_code' => '2005', 'name' => 'Kapilvastu', 'name_np' => 'कपिलबस्तु', 'status' => false],
            ['code' => '2060', 'province_code' => '2005', 'name' => 'Rukum (Eastern Part)', 'name_np' => 'रूकुम (पूर्वी भाग)', 'status' => false],
            ['code' => '2061', 'province_code' => '2005', 'name' => 'Rolpa', 'name_np' => 'रोल्पा', 'status' => false],
            ['code' => '2062', 'province_code' => '2005', 'name' => 'Pyuthan', 'name_np' => 'प्यूठान', 'status' => false],
            ['code' => '2063', 'province_code' => '2005', 'name' => 'Dang', 'name_np' => 'दाङ', 'status' => false],
            ['code' => '2064', 'province_code' => '2005', 'name' => 'Banke', 'name_np' => 'बाँके', 'status' => false],
            ['code' => '2065', 'province_code' => '2005', 'name' => 'Bardiya', 'name_np' => 'बर्दिया', 'status' => false],
        
            // Province 6 (2006)
            ['code' => '2070', 'province_code' => '2006', 'name' => 'Mugu', 'name_np' => 'मुगु', 'status' => false],
            ['code' => '2066', 'province_code' => '2006', 'name' => 'Rukum (Western Part)', 'name_np' => 'रूकुम (पश्चिम भाग)', 'status' => false],
            ['code' => '2067', 'province_code' => '2006', 'name' => 'Salyan', 'name_np' => 'सल्यान', 'status' => false],
            ['code' => '2068', 'province_code' => '2006', 'name' => 'Dolpa', 'name_np' => 'डोल्पा', 'status' => false],
            ['code' => '2074', 'province_code' => '2006', 'name' => 'Dailekh', 'name_np' => 'दैलेख', 'status' => false],
            ['code' => '2073', 'province_code' => '2006', 'name' => 'Jajarkot', 'name_np' => 'जाजरकोट', 'status' => false],
            ['code' => '2072', 'province_code' => '2006', 'name' => 'Kalikot', 'name_np' => 'कालिकोट', 'status' => false],
            ['code' => '2071', 'province_code' => '2006', 'name' => 'Humla', 'name_np' => 'हुम्ला', 'status' => false],
            ['code' => '2069', 'province_code' => '2006', 'name' => 'Jumla', 'name_np' => 'जुम्ला', 'status' => false],
            ['code' => '2075', 'province_code' => '2006', 'name' => 'Surkhet', 'name_np' => 'सुर्खेत', 'status' => false],
        
            // Province 7 (2007)
            ['code' => '2077', 'province_code' => '2007', 'name' => 'Bajhang', 'name_np' => 'बझाङ', 'status' => false],
            ['code' => '2078', 'province_code' => '2007', 'name' => 'Doti', 'name_np' => 'डोटी', 'status' => false],
            ['code' => '2082', 'province_code' => '2007', 'name' => 'Dadeldhura', 'name_np' => 'डडेल्धुरा', 'status' => false],
            ['code' => '2083', 'province_code' => '2007', 'name' => 'Kanchanpur', 'name_np' => 'कञ्चनपुर', 'status' => false],
            ['code' => '2084', 'province_code' => '2007', 'name' => 'Kailali', 'name_np' => 'कैलाली', 'status' => false],
            ['code' => '2080', 'province_code' => '2007', 'name' => 'Darchula', 'name_np' => 'दार्चुला', 'status' => false],
            ['code' => '2076', 'province_code' => '2007', 'name' => 'Bajura', 'name_np' => 'बाजुरा', 'status' => false],
            ['code' => '2081', 'province_code' => '2007', 'name' => 'Baitadi', 'name_np' => 'बैतडी', 'status' => false],
            ['code' => '2079', 'province_code' => '2007', 'name' => 'Achham', 'name_np' => 'अछाम', 'status' => false],
        ];

        $provinces = Province::all()->keyBy('code');
        foreach ($districts as $district) {
            $provinceId = $provinces->get($district['province_code'])->id ?? null;
            // Create the district with province_id
            District::create([
                'code' => $district['code'],
                'province_id' => $provinceId,
                'name' => $district['name'],
                'name_np' => $district['name_np'],
                'status' => $district['status'],
                'province_code' => $district['province_code'], // Optional: Keep province_code for reference
            ]);
        }
    }
}
