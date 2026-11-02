<?php

$rebranded = config('app.rebrand_profile_id');

return [
    'profile_id' => $rebranded ? 'Profile ID' : 'Academy ID',
    'profile_id_lower' => $rebranded ? 'Profile id' : 'Academy id',
];
