<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AttendanceSystemSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Clean Database
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('approval_logs')->truncate();
        DB::table('attendance_entries')->truncate();
        DB::table('weekly_attendances')->truncate();
        DB::table('employees')->truncate();
        DB::table('users')->truncate();
        DB::table('departments')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Create Admin (Active)
        $password = Hash::make('password');

        User::create([
            'name' => 'System Admin',
            'email' => 'admin@example.com',
            'password' => $password,
            'role' => 'admin',
            'email_verified_at' => now(),
            'is_active' => true,
        ]);

        // 3. Create a Generic Manager (Active) for testing
        User::create([
            'name' => 'General Manager',
            'email' => 'manager@example.com',
            'password' => $password,
            'role' => 'manager',
            'email_verified_at' => now(),
            'is_active' => true,
        ]);

        // 4. Define and Create Departments
        $deptMap = [
            'Commercial',
            'Design',
            'It',
            'Hr',
            'Finance',
            'Tender & Estimation',
            'Building',
            'Hse',
            'Legal',
            'Logistics',
            'Mep',
            'Operation',
            'Partnering',
            'Plant & Equipment',
            'Procurement',
            'Qaqc',
            'Office Management',
            'Infrastructure',
            'Store',
            'Planning' // Added Planning
        ];

        $deptModels = [];
        $existingCodes = [];

        foreach ($deptMap as $name) {
            // Apply correct casing
            $cleanName = str_replace(['&', ' '], '', $name);
            $baseCode = strtoupper(substr($cleanName, 0, 3));

            // Ensure unique code
            $displayCode = $baseCode;
            $counter = 1;

            // Manual overrides for known conflicts
            if ($name === 'Infrastructure') {
                $displayCode = 'INF';
            }
            if ($name === 'Planning') {
                $displayCode = 'PLN';
            }

            while (in_array($displayCode, $existingCodes)) {
                $displayCode = substr($baseCode, 0, 2) . $counter;
                $counter++;
            }
            $existingCodes[] = $displayCode;

            // Fix specific casing for acronyms
            $displayName = $name;
            if (in_array(strtoupper($name), ['IT', 'HR', 'HSE', 'MEP', 'QAQC'])) {
                $displayName = strtoupper($name);
            } else {
                $displayName = ucwords(strtolower($name));
                // Fix "&" casing
                $displayName = str_replace(' & ', ' & ', $displayName);
            }

            // Special fix for abbreviations in list if needed
            if ($name === 'It')
                $displayName = 'IT';
            if ($name === 'Hr')
                $displayName = 'HR';
            if ($name === 'Hse')
                $displayName = 'HSE';
            if ($name === 'Mep')
                $displayName = 'MEP';
            if ($name === 'Qaqc')
                $displayName = 'QAQC';

            $deptModels[$displayName] = Department::firstOrCreate([
                'name' => $displayName
            ], [
                'code' => $displayCode,
                'is_active' => true,
            ]);
        }

        // 5. Raw Employee Data
        // Format: Name Name [Name] Dept
        $rawData = [
            ['Abel Tadesse', 'IT'],
            ['Henok Petros', 'IT'],
            ['Nebyu Tito', 'IT'],
            ['Abyalew Tetsafe', 'HR'],
            ['Anduamlak Azmeraw', 'HR'],
            ['Bemnet Asmare', 'HR'],
            ['Endale Andualem', 'HR'],
            ['Hanna Asmamaw', 'HR'],
            ['Kidist Tadesse', 'HR'],
            ['Lidya Gebru', 'HR'],
            ['Maraki Eyassu', 'HR'],
            ['Rediet Shehicho', 'HR'],
            ['Shewatsehay Derbe', 'HR'],
            ['Tigist Goshu', 'HR'],
            ['Tigist Tsige', 'HR'],
            ['Almaz Amsalu', 'Finance'],
            ['Anchinalu Mussie', 'Store'],
            ['Beamlak Debebe', 'Finance'],
            ['Beamlak Moges', 'Store'],
            ['Bethelehem Deres', 'Finance'],
            ['Ethiopia Haile', 'Finance'],
            ['Fiker Addis', 'Finance'],
            ['Hanna Kebede', 'Finance'],
            ['Kumlachew Geze', 'Finance'],
            ['Mahlet Legesse', 'Store'],
            ['Marshet Tesfaye', 'Store'],
            ['Meron Temesgen', 'Finance'],
            ['Meron Yohannes', 'Store'],
            ['Meseret Minwuyelet', 'Finance'],
            ['Netsanet Tesfaye', 'Finance'],
            ['Tewabech Demeke', 'Store'],
            ['Tewdros Temesgen', 'Finance'],
            ['Yohannes Getacher', 'Finance'],
            ['Zekariyas Birhanu', 'Store'],
            ['Abrham Ghion', 'Design'],
            ['Dagim Melese', 'Design'],
            ['Dagim Tesfaye', 'Design'],
            ['Darimo Yohannes', 'Design'],
            ['Edeab Tesfaye', 'Design'],
            ['Eden Shiferaw', 'Design'],
            ['Elshaday Abate', 'Design'],
            ['Fikre Teklu', 'Design'],
            ['Fikrte Habtesilassie', 'Design'],
            ['Gelila Zelalem', 'Design'],
            ['Hanna Tesfaye', 'Design'],
            ['Mesay Lanta', 'Design'],
            ['Mesfin Agonafer', 'Design'],
            ['Metasebiya Alemayehu', 'Design'],
            ['Mihiret Tsehaye', 'Design'],
            ['Mussie Bekele', 'Design'],
            ['Rediat Kassa', 'Design'],
            ['Selamawit Girma', 'Design'],
            ['Shiferaw Mekonnen', 'Design'],
            ['Tekletsion Terefe', 'Design'],
            ['Tsinu Sime', 'Design'],
            ['Yohannis Birhanu', 'Design'],
            ['Yoseph Tamene', 'Design'],
            ['Bereket Zewongel', 'Tender & Estimation'],
            ['Dawit Metasebia', 'Commercial'],
            ['Elias Oumer', 'Commercial'],
            ['Hanna Abayneh', 'Commercial'],
            ['Kidist Asfer', 'Tender & Estimation'],
            ['Leul Mesfin', 'Commercial'],
            ['Mekbeb Bayu', 'Tender & Estimation'],
            ['Samuel Aysheshim', 'Tender & Estimation'],
            ['Seble Dagnaw', 'Commercial'],
            ['Seble Hailemaryam', 'Tender & Estimation'],
            ['Segen Tsegai', 'Commercial'],
            ['Tewodros Ashebir', 'Commercial'],
            ['Yohannes Ameha', 'Commercial'],
            ['Zinaw Teshale', 'Tender & Estimation'],
            ['Solomon Asrat', 'Building'],
            ['Assefa Tegenu', 'HSE'],
            ['Haniel Mekonnen', 'HSE'],
            ['Yemisrach Nigusse', 'HSE'],
            ['Kalkidan Asefa', 'Legal'],
            ['Abreham Asefa', 'Logistics'],
            ['Alemu Beletu', 'Logistics'],
            ['Assefa Bekele', 'Logistics'],
            ['Berihun Belay', 'Logistics'],
            ['Birhanu Bayisa', 'Logistics'],
            ['Chalchesa Moti', 'Logistics'],
            ['Eyob Mersha', 'Logistics'],
            ['Fikre Tegegn', 'Logistics'],
            ['Getenet Kassie', 'Logistics'],
            ['Gurmesa Beyene', 'Logistics'],
            ['Guta Bekel', 'Logistics'],
            ['Habtamu Bezu', 'Logistics'],
            ['Hailu Girma', 'Logistics'],
            ['Million Fikadu', 'Logistics'],
            ['Robel Shewaye', 'Logistics'],
            ['Sawed Muzemil', 'Logistics'],
            ['Sintayehu Anteneh', 'Logistics'],
            ['Tamru Assefa', 'Logistics'],
            ['Tesfamaryam Deriba', 'Logistics'],
            ['Abraham Getachew', 'MEP'],
            ['Ashenafi Gebrekidan', 'MEP'],
            ['Ephrem Habtamu', 'MEP'],
            ['Eyob Yeshitila', 'MEP'],
            ['Furtuna Ermias', 'MEP'],
            ['Girma Terefe', 'MEP'],
            ['Haregua Birhanu', 'MEP'],
            ['Kidest Girmaye', 'MEP'],
            ['Redwan Adem', 'MEP'],
            ['Shimeles Kalayou', 'MEP'],
            ['Tigist Engidawork', 'MEP'],
            ['Tinbite Daniel', 'MEP'],
            ['Belet Kassa', 'Operation'],
            ['Fitsum Bayisa', 'Operation'],
            ['Gebre Nega', 'Operation'],
            ['Lea Mekonenn', 'Operation'],
            ['Nolawi Asfaw', 'Operation'],
            ['Selamawit Melaku', 'Operation'],
            ['Sileshe Tadesse', 'Operation'],
            ['Tewodros Bekele', 'Operation'],
            ['Belhen G/Hiwot', 'Partnering'],
            ['Efrem Fisseha', 'Partnering'],
            ['Eyob Shimeles', 'Partnering'],
            ['Mintesenot Aregaw', 'Partnering'],
            ['Zeyen Kerae', 'Partnering'],
            ['Abay Tayachew', 'Plant & Equipment'],
            ['Anteneh Mengistu', 'Plant & Equipment'],
            ['Ashenafi Gezahegn', 'Plant & Equipment'],
            ['Dagimsew Yilak', 'Plant & Equipment'],
            ['Ermiyas Nigussie Belachew', 'Plant & Equipment'],
            ['Fikremariam Dagnachew', 'Plant & Equipment'],
            ['Geleta Muleta', 'Plant & Equipment'],
            ['Girmachew Assefa', 'Plant & Equipment'],
            ['Negash Getu', 'Plant & Equipment'],
            ['Rahel Eyuel', 'Plant & Equipment'],
            ['Yohannes Desalegn', 'Plant & Equipment'],
            ['Anteneh Talargachew', 'Procurement'],
            ['Ardet Debashu', 'Procurement'],
            ['Bekale Tezera', 'Procurement'],
            ['Betelhem Belachew', 'Procurement'],
            ['Betelhem Desaleg', 'Procurement'],
            ['Bizuayehu G/Weld', 'Procurement'],
            ['Daniel Dagnachew', 'Procurement'],
            ['Dawit Bekele', 'Procurement'],
            ['Eyob Girma', 'Procurement'],
            ['Eyob Seifu', 'Procurement'],
            ['Fasika Behailu', 'Procurement'],
            ['Feven Deneke', 'Procurement'],
            ['Helen Teshome', 'Procurement'],
            ['Henok Aserat', 'Procurement'],
            ['Hilina Alemu', 'Procurement'],
            ['Nardos Asefa', 'Procurement'],
            ['Natnael Gizaw', 'Procurement'],
            ['Sena Girma', 'Procurement'],
            ['Surafel Hailye', 'Procurement'],
            ['Tsegaye Bayisa', 'Procurement'],
            ['Yalew Yebeltal', 'Procurement'],
            ['Yanet Abebe', 'Procurement'],
            ['Frezer Debebe', 'QAQC'],
            ['Habtamu Miheret', 'QAQC'],
            ['Iskiyas Yigezu', 'QAQC'],
            ['Wondimagen Habtamu', 'QAQC'],
            ['Senayit Wondimu', 'Commercial'],
            ['Yishak Ergetu', 'MEP'],
            ['Beza Wedajo', 'Tender & Estimation'],
            ['Mestawot Ageze', 'Finance'],
            ['Gizachew Maybet', 'Finance'],
            ['Genet Wondimu', 'Finance'],
            ['Birhanu Ayenew', 'Operation'],
            ['Tilahun Zelalem', 'MEP'],
            ['Zewdie Afework', 'Procurement'],
            ['Abel Tadesse', 'Procurement'],
            ['Ermias Endashaw', 'QAQC'],
            ['Getahun Niguse', 'Procurement'],
            ['Henok Terefe', 'Plant & Equipment'],
            ['Yordanos Bezu', 'Procurement'],
            ['Zemen Aweke', 'Finance'],
            ['Semira Mohammed', 'Tender & Estimation'],
            ['Mahlet Dessalegn', 'Design'],
            ['Demeke Alemkere', 'Procurement'],
            ['Amanuel Zerihun', 'Operation'],
            ['Betelhem Lemma', 'Procurement'],
            ['Mahlet Kalid', 'HSE'],
            ['Yonas Alagaw', 'Commercial'],
            ['Abem Murad', 'Commercial'],
            ['Bereket Tinsae', 'MEP'],
            ['Mahlet Teklehaymanot', 'MEP'],
            ['Tigist Azaze', 'MEP'],
            ['Liya Atilabachew', 'QAQC'],
            ['Martha Misge', 'HR'],
            ['Seblewongel Degaregre', 'HR'],
            ['Mikiyas Melaku', 'Procurement'],
            ['Doi Alemayehu', 'Tender & Estimation'],
            ['Eyerus Girma', 'Tender & Estimation'],
            ['Ermias Wodajo', 'Legal'],
            ['Elda Daniel', 'HR'],
            ['Birhane Gebeyehu', 'Office Management'],
            ['Mahlet Wondemu', 'Office Management'],
            ['Haymanot Tekeste', 'Office Management'],
            ['Wasihun Tarekegn', 'Finance'],
            ['Amare Demissie', 'Finance'],
            ['Atalay Mesafint', 'Plant & Equipment'],
            ['Ebrahim Seid', 'Plant & Equipment'],
            ['Biruk Simegnew', 'Procurement'],
            ['Ahadu Masresha', 'Procurement'],
            ['Dagmawi Daniel', 'Procurement'],
            ['Etsegenet Teshome', 'Procurement'],
            ['Nardos Hailu', 'Procurement'],
            ['Rediet Mulugeta', 'Procurement'],
            ['Yibeltal Asmare', 'Procurement'],
            ['Alemayehu Solomon', 'Finance'],
            ['kidist Adenew', 'Design'],
            ['Rodas Abrham', 'Procurement'],
            ['Negasi Hailemichael', 'MEP'],
            ['Rediet Kibrom', 'HR'],
            ['Sidise Tesfaye', 'IT'],
            ['Elbethel Zewdu', 'Design'],
            ['Kalkidan Shimelis', 'Procurement'],
            ['Anduamlak Temesgen', 'IT'],
            ['Muluneh Sisay', 'Design'],
            ['Nurilgn Desalew', 'MEP'],
            ['Alebachew Muchie', 'Operation'],
            ['Yosef Engida', 'Logistics'],
            ['Shewakena Engida', 'Logistics'],
            ['Masresha Eshetu', 'Infrastructure'],
            ['Dawit Zerihun', 'Procurement'],
            ['Getachew Tamir', 'Store'],
            ['Tamiru Neku', 'Commercial'],
            ['Zebiba Murad', 'Finance'],
            ['Abenezer Mesfin', 'Operation'],
            ['Belay Kassa', 'Operation'],
            ['Hanna G/Michael', 'HR'],
            ['Betelhem Abiyu', 'HR'],
            ['Kalkidan Taye', 'HR'],
            ['Asheber Ketema', 'MEP'],
            ['Etsubdink Melkamu', 'Finance'],
            ['Eyerusalem Kefyalew', 'MEP'],
            ['Hilina Gulilat', 'MEP'],
            ['Hiwot Workineh', 'Commercial'],
            ['Kidist Teklay', 'Finance'],
            ['Magartu Bezuayehu', 'Procurement'],
            ['Maraki Nahusenay', 'HR'],
            ['Muluken Tirualem', 'Commercial'],
            ['Roman Leul', 'Commercial'],
            ['Dessalegn Tamirat', 'Commercial'],
            ['Meti Bejiga', 'Commercial'],
            ['Dereje Assefa', 'Finance'],
            ['Mekdes Kefalew', 'Procurement'],
            ['Meron Dejene', 'QAQC'],
            ['Tsegazeab Aygemit', 'Design'],
            ['Desalegn Eshetu', 'HSE'],
            ['Olivia Desalgn', 'Design'],
            ['Daniel Girma', 'IT'],
            ['Amanuel Geremew', 'Finance'],
            ['Tesfahun Belete', 'Design'],
            ['Ananya Tesfaye', 'Finance'],
            ['Etsegnet Belay', 'HR'],
            ['Tigist Bekele', 'HR'],
            ['Addisalem Abera', 'Design'],
            ['Anteneh Wujira', 'Design'],
            ['Dagimawi Abebe', 'Design'],
            ['Elbethel Wudneh', 'Commercial'],
            ['Geleta Teferi', 'Design'],
            ['Hiko Seifu', 'Procurement'],
            ['Kalkidan Wesenyeles', 'Design'],
            ['Melat Getastegne', 'Procurement'],
            ['Petros Lidetu', 'Design'],
            ['Regib Ashebir', 'Procurement'],
            ['Selamawit Dache', 'Design'],
            ['Tamiru Belachew', 'MEP'],
            ['Tinbete Yonas', 'Procurement'],
            ['Mekdelawit Afework', 'HR'],
            ['Fasika Minwyelet', 'Operation'],
        ];

        // 6. Process Records
        foreach ($rawData as $index => $row) {
            $name = $row[0];
            $deptNameRaw = $row[1];

            // Normalize Dept Name
            $deptNameNormalized = $deptNameRaw;

            // Find Department ID (use the map to get clean name, or fallback)
            // Note: department names in $deptModels key are ucwords/upper.
            // We need to match $deptNameNormalized to keys of $deptModels.
            // "Finance" -> "Finance"
            // "Plant & Equipment" -> "Plant & Equipment"
            // "IT" -> "IT"

            // Helper to match key
            $deptKey = null;
            foreach ($deptModels as $key => $model) {
                if (strcasecmp($key, $deptNameNormalized) === 0) {
                    $deptKey = $key;
                    break;
                }
            }

            if (!$deptKey) {
                // Fallback: create if missing (though we predefined them)
                $deptModels[$deptNameNormalized] = Department::firstOrCreate(
                    ['name' => $deptNameNormalized],
                    ['code' => strtoupper(substr($deptNameNormalized, 0, 3)), 'is_active' => true]
                );
                $deptKey = $deptNameNormalized;
            }

            $dept = $deptModels[$deptKey];

            // Generate ID: EEC-0001
            $employeeId = 'EEC-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT);

            // Create User (Inactive)
            // Generate email: first.last@example.com
            $nameParts = explode(' ', $name);
            $firstName = $nameParts[0];
            $lastName = end($nameParts);
            $email = strtolower($firstName . '.' . $lastName . '@example.com');

            // Uniquify email if duplicate
            $cnt = 1;
            while (User::where('email', $email)->exists()) {
                $email = strtolower($firstName . '.' . $lastName . $cnt . '@example.com');
                $cnt++;
            }

            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'role' => 'user',
                'department_id' => $dept->id,
                'employee_id' => $employeeId,
                'email_verified_at' => now(), // verified but inactive
                'is_active' => false,
            ]);

            // Create Employee Record (Inactive)
            Employee::create([
                'user_id' => $user->id,
                'department_id' => $dept->id,
                'employee_id' => $employeeId,
                'first_name' => $firstName,
                'last_name' => count($nameParts) > 1 ? implode(' ', array_slice($nameParts, 1)) : '',
                'email' => $email,
                'phone' => '09' . mt_rand(10000000, 99999999),
                'date_of_joining' => now(),
                'position' => 'Staff',
                'employment_type' => 'full_time',
                'is_active' => false,
            ]);
        }
    }
}

