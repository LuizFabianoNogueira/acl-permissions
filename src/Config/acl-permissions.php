<?php

return [

    /**
     * Define the column type of the id in user table.
     */
    'user_id_is' => 'UUID', # INTEGER or UUID

    /**
     * Define the column type of the id in user table.
     */
    'role_id_is' => 'UUID', # INTEGER or UUID

    /**
     * The user model that should be used to retrieve your permissions.
     */
    'user' => App\Models\User::class,

    /**
     * The role model that should be used to retrieve your permissions.
     */
    'role' => null

];
