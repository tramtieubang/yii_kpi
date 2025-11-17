<?php

return [

    'departments' => [ 
        'class' => 'app\modules\departments\Module', 
    ],

    'employees' => [ 
        'class' => 'app\modules\employees\Module', 
    ],  
    
    'kpi' => [ 
        'class' => 'app\modules\kpi\Module', 
    ],

    'work-registered' => [ 
        'class' => 'app\modules\work_registered\Module', 
    ],

    'kpi-evaluation' => [ 
        'class' => 'app\modules\kpi_evaluation\Module', 
    ],

    'home' => [ 
        'class' => 'app\modules\home\Module', 
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