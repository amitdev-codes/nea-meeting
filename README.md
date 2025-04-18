# Env modification
keep log channel daily
Asia/Kathmandu

App-locale:np  for language translations to nepali

yarn add datatables.net datatables.net-bs5 datatables.net-buttons datatables.net-buttons-bs5 datatables.net-fixedcolumns datatables.net-fixedcolumns-bs5 datatables.net-fixedheader datatables.net-fixedheader-bs5 datatables.net-responsive datatables.net-responsive-bs5 datatables.net-rowgroup datatables.net-rowgroup-bs5 datatables.net-select datatables.net-select-bs5 jquery-datatables-checkboxes jszip pdfmake

# for inline editing province check
# for modal form check district


# master data for
1. crops- crops variety(english),nepali,crop name
2. districts active and in districts show province for 
3 .rural municipalities show district province.
4. groups import make we have already excel. or make seeder from csv.
5. component a,b,c like3
5. sub componenet livestock,crop,business,nutrition

# make dynamic all
php artisan make:all SubComponent For Default Structure (Non-Modular)
php artisan make:all SubComponent Master For Modular Structure

here SubComponent is model name

# master data
1.designation or position
2. componenet A,b,c
3. subcomponnet livestock,crop
4. cluster code,name,name-np,status

5. cluster types 
6. expenditure categories 

5. . lmbis module
  -lmbis section -section_code,section_name,expenditure category
  -lmbis activity -fiscal year,lmbissection,lmbis code,lmbis activity name,pim activity name

  7. master
  sectors- code,name,name_np,status,
  sub sectors- code,sector_id,name,name_np,status,
  castes- code,name,name-np and datas are dalit,adhibasi/janjati,brahmins/chhetri,muslim,others
  starter_categories -code,name,name_np,status and datas are early,late

  # user details
  email,username,password,mobile_no,phone,position/designation,componenet,subcomponenet,province,district,locallevel,ward no,street name


# global values
6. need current fiscal year id so keep in site settings.

# use of console command make all
# Basic usage
php artisan make:all User

# With module
php artisan make:all User Admin

# Specify table (if different from plural resource name)
php artisan make:all User Admin --table=admin_users

# Specify database connection
php artisan make:all User --connection=tenant
#group members
group,beneficiary_name,name_np,unique householdid,unique id for beneficiary,designation,sex,age,castes,marginalized,family members male,female,total,remarks.

# for bulk delete to workout always in render checkbox and initbulkdeletescript keep strlower model name foolowed by ids
  like component_ids[] and for mix keep indicatorsector_ids[] if table is indicator_sectors and model is IndicatorSector

  # accessUrl= http://127.0.0.1:8000/meetings/?code=admin