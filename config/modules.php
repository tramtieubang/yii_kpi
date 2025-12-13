<?php

return [

    'department' => [ 
        'class' => 'app\modules\department\Module', 
    ],

    'employees' => [ 
        'class' => 'app\modules\employees\Module', 
    ],  

    'staff' => [ 
        'class' => 'app\modules\staff\Module', 
    ],
    
    'kpi' => [ 
        'class' => 'app\modules\kpi\Module', 
    ],

    'work-registered' => [ 
        'class' => 'app\modules\work_registered\Module', 
    ],

    'work-assignment' => [ 
        'class' => 'app\modules\work_assignment\Module', 
    ],

    'kpi-evaluation' => [ 
        'class' => 'app\modules\kpi_evaluation\Module', 
    ],

    'business-fields' => [ 
        'class' => 'app\modules\business_fields\Module', 
    ],

    'positions' => [ 
        'class' => 'app\modules\positions\Module', 
    ],

    'home' => [ 
        'class' => 'app\modules\home\Module', 
    ],

    'reports' => [ 
        'class' => 'app\modules\reports\Module', 
    ],
     
    // phan quyen
    /* 'user' => [ 
        'class' => 'app\modules\userManagements\user\Module', 
    ],
    'role' => [ 
        'class' => 'app\modules\userManagements\role\Module', 
    ],
    'permissiongroup' => [ 
        'class' => 'app\modules\userManagements\permissionGroup\Module', 
    ],
    'permission' => [ 
        'class' => 'app\modules\userManagements\permission\Module', 
    ],  */

   'user_management' => [
        'class' => 'app\modules\user_management\Module',
    ],

   
];