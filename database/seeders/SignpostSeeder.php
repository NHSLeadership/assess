<?php

namespace Database\Seeders;

use App\Models\Signpost;
use Illuminate\Database\Seeder;

class SignpostSeeder extends Seeder
{
    public function run(): void
    {
        $signposts = [
            // Developing self → Personal productivity and wellbeing (node_ids: 3,4,5)
            ['node_id' => 3,  'framework_variant_option_id' => 1, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://learninghub.nhs.uk/Resource/71437/Item" target="_blank" rel="noopener">https://learninghub.nhs.uk/Resource/71437/Item</a></li>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_43876&programmeId=43876" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_43876&programmeId=43876</a></li>
</ul>
GUID, ],
            ['node_id' => 3,  'framework_variant_option_id' => 2, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://learninghub.nhs.uk/Resource/71437/Item" target="_blank" rel="noopener">https://learninghub.nhs.uk/Resource/71437/Item</a></li>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_43876&programmeId=43876" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_43876&programmeId=43876</a></li>
</ul>
GUID, ],
            ['node_id' => 3,  'framework_variant_option_id' => 3, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://learninghub.nhs.uk/Resource/71437/Item" target="_blank" rel="noopener">https://learninghub.nhs.uk/Resource/71437/Item</a></li>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_43876&programmeId=43876" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_43876&programmeId=43876</a></li>
</ul>
GUID, ],
            ['node_id' => 3,  'framework_variant_option_id' => 4, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://learninghub.nhs.uk/Resource/71437/Item" target="_blank" rel="noopener">https://learninghub.nhs.uk/Resource/71437/Item</a></li>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_43876&programmeId=43876" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_43876&programmeId=43876</a></li>
</ul>
GUID, ],

            ['node_id' => 4,  'framework_variant_option_id' => 1, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://www.england.nhs.uk/supporting-our-nhs-people/health-and-wellbeing-programmes/nhs-health-and-wellbeing-framework/" target="_blank" rel="noopener">https://www.england.nhs.uk/supporting-our-nhs-people/health-and-wellbeing-programmes/nhs-health-and-wellbeing-framework/</a></li>
</ul>
GUID, ],
            ['node_id' => 4,  'framework_variant_option_id' => 2, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://www.england.nhs.uk/supporting-our-nhs-people/health-and-wellbeing-programmes/nhs-health-and-wellbeing-framework/" target="_blank" rel="noopener">https://www.england.nhs.uk/supporting-our-nhs-people/health-and-wellbeing-programmes/nhs-health-and-wellbeing-framework/</a></li>
</ul>
GUID, ],
            ['node_id' => 4,  'framework_variant_option_id' => 3, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://www.england.nhs.uk/supporting-our-nhs-people/health-and-wellbeing-programmes/nhs-health-and-wellbeing-framework/" target="_blank" rel="noopener">https://www.england.nhs.uk/supporting-our-nhs-people/health-and-wellbeing-programmes/nhs-health-and-wellbeing-framework/</a></li>
</ul>
GUID, ],
            ['node_id' => 4,  'framework_variant_option_id' => 4, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://www.england.nhs.uk/supporting-our-nhs-people/health-and-wellbeing-programmes/nhs-health-and-wellbeing-framework/" target="_blank" rel="noopener">https://www.england.nhs.uk/supporting-our-nhs-people/health-and-wellbeing-programmes/nhs-health-and-wellbeing-framework/</a></li>
</ul>
GUID, ],

            ['node_id' => 5,  'framework_variant_option_id' => 1, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_58160_58162_33575&programmeId=58160" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_58160_58162_33575&programmeId=58160</a></li>
</ul>
GUID, ],
            ['node_id' => 5,  'framework_variant_option_id' => 2, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_58160_58162_33575&programmeId=58160" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_58160_58162_33575&programmeId=58160</a></li>
</ul>
GUID, ],
            ['node_id' => 5,  'framework_variant_option_id' => 3, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_58160_58162_33575&programmeId=58160" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_58160_58162_33575&programmeId=58160</a></li>
</ul>
GUID, ],
            ['node_id' => 5,  'framework_variant_option_id' => 4, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_58160_58162_33575&programmeId=58160" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_58160_58162_33575&programmeId=58160</a></li>
</ul>
GUID, ],

            // Developing self → Communicating well (node_ids: 7,8,9)
            ['node_id' => 7,  'framework_variant_option_id' => 1, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_33501&programmeId=33501" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_33501&programmeId=33501</a></li>
</ul>
GUID, ],
            ['node_id' => 7,  'framework_variant_option_id' => 2, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_33501&programmeId=33501" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_33501&programmeId=33501</a></li>
</ul>
GUID, ],
            ['node_id' => 7,  'framework_variant_option_id' => 3, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_33501&programmeId=33501" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_33501&programmeId=33501</a></li>
</ul>
GUID, ],
            ['node_id' => 7,  'framework_variant_option_id' => 4, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_33501&programmeId=33501" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_33501&programmeId=33501</a></li>
</ul>
GUID, ],

            ['node_id' => 8,  'framework_variant_option_id' => 1, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_58160_58162_33575&programmeId=58160" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_58160_58162_33575&programmeId=58160</a></li>
</ul>
GUID, ],
            ['node_id' => 8,  'framework_variant_option_id' => 2, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_58160_58162_33575&programmeId=58160" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_58160_58162_33575&programmeId=58160</a></li>
</ul>
GUID, ],
            ['node_id' => 8,  'framework_variant_option_id' => 3, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_58160_58162_33575&programmeId=58160" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_58160_58162_33575&programmeId=58160</a></li>
</ul>
GUID, ],
            ['node_id' => 8,  'framework_variant_option_id' => 4, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_58160_58162_33575&programmeId=58160" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_58160_58162_33575&programmeId=58160</a></li>
</ul>
GUID, ],

            ['node_id' => 9,  'framework_variant_option_id' => 1, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_33501&programmeId=33501" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_33501&programmeId=33501</a></li>
</ul>
GUID, ],
            ['node_id' => 9,  'framework_variant_option_id' => 2, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_33501&programmeId=33501" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_33501&programmeId=33501</a></li>
</ul>
GUID, ],
            ['node_id' => 9,  'framework_variant_option_id' => 3, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_33501&programmeId=33501" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_33501&programmeId=33501</a></li>
</ul>
GUID, ],
            ['node_id' => 9,  'framework_variant_option_id' => 4, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_33501&programmeId=33501" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_33501&programmeId=33501</a></li>
</ul>
GUID, ],

            // Developing self → Responsibility and integrity (node_ids: 11,12,13)
            ['node_id' => 11, 'framework_variant_option_id' => 1, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_44026&programmeId=44026" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_44026&programmeId=44026</a></li>
</ul>
GUID, ],
            ['node_id' => 11, 'framework_variant_option_id' => 2, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_44026&programmeId=44026" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_44026&programmeId=44026</a></li>
</ul>
GUID, ],
            ['node_id' => 11, 'framework_variant_option_id' => 3, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_44026&programmeId=44026" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_44026&programmeId=44026</a></li>
</ul>
GUID, ],
            ['node_id' => 11, 'framework_variant_option_id' => 4, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_44026&programmeId=44026" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_44026&programmeId=44026</a></li>
</ul>
GUID, ],

            ['node_id' => 12, 'framework_variant_option_id' => 1, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://www.leadershipacademy.nhs.uk/bitesize/short-courses/authentic-leadership/" target="_blank" rel="noopener">https://www.leadershipacademy.nhs.uk/bitesize/short-courses/authentic-leadership/</a></li>
</ul>
GUID, ],
            ['node_id' => 12, 'framework_variant_option_id' => 2, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://www.leadershipacademy.nhs.uk/bitesize/short-courses/authentic-leadership/" target="_blank" rel="noopener">https://www.leadershipacademy.nhs.uk/bitesize/short-courses/authentic-leadership/</a></li>
</ul>
GUID, ],
            ['node_id' => 12, 'framework_variant_option_id' => 3, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://www.leadershipacademy.nhs.uk/bitesize/short-courses/authentic-leadership/" target="_blank" rel="noopener">https://www.leadershipacademy.nhs.uk/bitesize/short-courses/authentic-leadership/</a></li>
</ul>
GUID, ],
            ['node_id' => 12, 'framework_variant_option_id' => 4, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://www.leadershipacademy.nhs.uk/bitesize/short-courses/authentic-leadership/" target="_blank" rel="noopener">https://www.leadershipacademy.nhs.uk/bitesize/short-courses/authentic-leadership/</a></li>
</ul>
GUID, ],

            ['node_id' => 13, 'framework_variant_option_id' => 1, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://www.england.nhs.uk/supporting-our-nhs-people/health-and-wellbeing-programmes/civility-and-respect/" target="_blank" rel="noopener">https://www.england.nhs.uk/supporting-our-nhs-people/health-and-wellbeing-programmes/civility-and-respect/</a></li>
  <li><a href="https://www.leadershipacademy.nhs.uk/programmes/core-managers-developing-inclusive-workplaces/" target="_blank" rel="noopener">https://www.leadershipacademy.nhs.uk/programmes/core-managers-developing-inclusive-workplaces/</a></li>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_58335&programmeId=58335" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_58335&programmeId=58335</a></li>
</ul>
GUID, ],
            ['node_id' => 13, 'framework_variant_option_id' => 2, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://www.england.nhs.uk/supporting-our-nhs-people/health-and-wellbeing-programmes/civility-and-respect/" target="_blank" rel="noopener">https://www.england.nhs.uk/supporting-our-nhs-people/health-and-wellbeing-programmes/civility-and-respect/</a></li>
  <li><a href="https://www.leadershipacademy.nhs.uk/programmes/core-managers-developing-inclusive-workplaces/" target="_blank" rel="noopener">https://www.leadershipacademy.nhs.uk/programmes/core-managers-developing-inclusive-workplaces/</a></li>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_58335&programmeId=58335" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_58335&programmeId=58335</a></li>
</ul>
GUID, ],
            ['node_id' => 13, 'framework_variant_option_id' => 3, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://www.england.nhs.uk/supporting-our-nhs-people/health-and-wellbeing-programmes/civility-and-respect/" target="_blank" rel="noopener">https://www.england.nhs.uk/supporting-our-nhs-people/health-and-wellbeing-programmes/civility-and-respect/</a></li>
  <li><a href="https://www.leadershipacademy.nhs.uk/programmes/core-managers-developing-inclusive-workplaces/" target="_blank" rel="noopener">https://www.leadershipacademy.nhs.uk/programmes/core-managers-developing-inclusive-workplaces/</a></li>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_58335&programmeId=58335" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_58335&programmeId=58335</a></li>
</ul>
GUID, ],
            ['node_id' => 13, 'framework_variant_option_id' => 4, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://www.england.nhs.uk/supporting-our-nhs-people/health-and-wellbeing-programmes/civility-and-respect/" target="_blank" rel="noopener">https://www.england.nhs.uk/supporting-our-nhs-people/health-and-wellbeing-programmes/civility-and-respect/</a></li>
  <li><a href="https://www.leadershipacademy.nhs.uk/programmes/core-managers-developing-inclusive-workplaces/" target="_blank" rel="noopener">https://www.leadershipacademy.nhs.uk/programmes/core-managers-developing-inclusive-workplaces/</a></li>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_58335&programmeId=58335" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_58335&programmeId=58335</a></li>
</ul>
GUID, ],

            // Managing people and resources → Building teams (node_ids: 16,17,18)
            ['node_id' => 16, 'framework_variant_option_id' => 1, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://devweb.e-lfhtech.org.uk/programmes/engaging-with-people-and-communities/" target="_blank" rel="noopener">https://devweb.e-lfhtech.org.uk/programmes/engaging-with-people-and-communities/</a></li>
  <li><a href="https://learninghub.nhs.uk/Resource/60930/Item" target="_blank" rel="noopener">https://learninghub.nhs.uk/Resource/60930/Item</a></li>
  <li><a href="https://learninghub.nhs.uk/resource/27337" target="_blank" rel="noopener">https://learninghub.nhs.uk/resource/27337</a></li>
  <li><a href="https://www.leadershipacademy.nhs.uk/bitesize/short-courses/team-and-group-dynamics/" target="_blank" rel="noopener">https://www.leadershipacademy.nhs.uk/bitesize/short-courses/team-and-group-dynamics/</a></li>
</ul>
GUID, ],
            ['node_id' => 16, 'framework_variant_option_id' => 2, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://devweb.e-lfhtech.org.uk/programmes/engaging-with-people-and-communities/" target="_blank" rel="noopener">https://devweb.e-lfhtech.org.uk/programmes/engaging-with-people-and-communities/</a></li>
  <li><a href="https://learninghub.nhs.uk/Resource/60930/Item" target="_blank" rel="noopener">https://learninghub.nhs.uk/Resource/60930/Item</a></li>
  <li><a href="https://learninghub.nhs.uk/resource/27337" target="_blank" rel="noopener">https://learninghub.nhs.uk/resource/27337</a></li>
  <li><a href="https://www.leadershipacademy.nhs.uk/bitesize/short-courses/team-and-group-dynamics/" target="_blank" rel="noopener">https://www.leadershipacademy.nhs.uk/bitesize/short-courses/team-and-group-dynamics/</a></li>
</ul>
GUID, ],
            ['node_id' => 16, 'framework_variant_option_id' => 3, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://devweb.e-lfhtech.org.uk/programmes/engaging-with-people-and-communities/" target="_blank" rel="noopener">https://devweb.e-lfhtech.org.uk/programmes/engaging-with-people-and-communities/</a></li>
  <li><a href="https://learninghub.nhs.uk/Resource/60930/Item" target="_blank" rel="noopener">https://learninghub.nhs.uk/Resource/60930/Item</a></li>
  <li><a href="https://learninghub.nhs.uk/resource/27337" target="_blank" rel="noopener">https://learninghub.nhs.uk/resource/27337</a></li>
  <li><a href="https://www.leadershipacademy.nhs.uk/bitesize/short-courses/team-and-group-dynamics/" target="_blank" rel="noopener">https://www.leadershipacademy.nhs.uk/bitesize/short-courses/team-and-group-dynamics/</a></li>
</ul>
GUID, ],
            ['node_id' => 16, 'framework_variant_option_id' => 4, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://devweb.e-lfhtech.org.uk/programmes/engaging-with-people-and-communities/" target="_blank" rel="noopener">https://devweb.e-lfhtech.org.uk/programmes/engaging-with-people-and-communities/</a></li>
  <li><a href="https://learninghub.nhs.uk/Resource/60930/Item" target="_blank" rel="noopener">https://learninghub.nhs.uk/Resource/60930/Item</a></li>
  <li><a href="https://learninghub.nhs.uk/resource/27337" target="_blank" rel="noopener">https://learninghub.nhs.uk/resource/27337</a></li>
  <li><a href="https://www.leadershipacademy.nhs.uk/bitesize/short-courses/team-and-group-dynamics/" target="_blank" rel="noopener">https://www.leadershipacademy.nhs.uk/bitesize/short-courses/team-and-group-dynamics/</a></li>
</ul>
GUID, ],

            ['node_id' => 17, 'framework_variant_option_id' => 1, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://www.england.nhs.uk/supporting-our-nhs-people/health-and-wellbeing-programmes/violence-prevention-and-safety/" target="_blank" rel="noopener">https://www.england.nhs.uk/supporting-our-nhs-people/health-and-wellbeing-programmes/violence-prevention-and-safety/</a></li>
</ul>
GUID, ],
            ['node_id' => 17, 'framework_variant_option_id' => 2, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://www.england.nhs.uk/supporting-our-nhs-people/health-and-wellbeing-programmes/violence-prevention-and-safety/" target="_blank" rel="noopener">https://www.england.nhs.uk/supporting-our-nhs-people/health-and-wellbeing-programmes/violence-prevention-and-safety/</a></li>
</ul>
GUID, ],
            ['node_id' => 17, 'framework_variant_option_id' => 3, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://www.england.nhs.uk/supporting-our-nhs-people/health-and-wellbeing-programmes/violence-prevention-and-safety/" target="_blank" rel="noopener">https://www.england.nhs.uk/supporting-our-nhs-people/health-and-wellbeing-programmes/violence-prevention-and-safety/</a></li>
</ul>
GUID, ],
            ['node_id' => 17, 'framework_variant_option_id' => 4, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://www.england.nhs.uk/supporting-our-nhs-people/health-and-wellbeing-programmes/violence-prevention-and-safety/" target="_blank" rel="noopener">https://www.england.nhs.uk/supporting-our-nhs-people/health-and-wellbeing-programmes/violence-prevention-and-safety/</a></li>
</ul>
GUID, ],

            ['node_id' => 18, 'framework_variant_option_id' => 1, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_58335&programmeId=58335" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_58335&programmeId=58335</a></li>
</ul>
GUID, ],
            ['node_id' => 18, 'framework_variant_option_id' => 2, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_58335&programmeId=58335" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_58335&programmeId=58335</a></li>
</ul>
GUID, ],
            ['node_id' => 18, 'framework_variant_option_id' => 3, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_58335&programmeId=58335" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_58335&programmeId=58335</a></li>
</ul>
GUID, ],
            ['node_id' => 18, 'framework_variant_option_id' => 4, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_58335&programmeId=58335" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_58335&programmeId=58335</a></li>
</ul>
GUID, ],

            // Managing people and resources → Performance and delivery (node_ids: 20,21,22)
            ['node_id' => 20, 'framework_variant_option_id' => 1, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://www.england.nhs.uk/long-read/the-expectations-of-line-managers-in-relation-to-people-management/" target="_blank" rel="noopener">https://www.england.nhs.uk/long-read/the-expectations-of-line-managers-in-relation-to-people-management/</a></li>
</ul>
GUID, ],
            ['node_id' => 20, 'framework_variant_option_id' => 2, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://www.england.nhs.uk/long-read/the-expectations-of-line-managers-in-relation-to-people-management/" target="_blank" rel="noopener">https://www.england.nhs.uk/long-read/the-expectations-of-line-managers-in-relation-to-people-management/</a></li>
</ul>
GUID, ],
            ['node_id' => 20, 'framework_variant_option_id' => 3, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://www.england.nhs.uk/long-read/the-expectations-of-line-managers-in-relation-to-people-management/" target="_blank" rel="noopener">https://www.england.nhs.uk/long-read/the-expectations-of-line-managers-in-relation-to-people-management/</a></li>
</ul>
GUID, ],
            ['node_id' => 20, 'framework_variant_option_id' => 4, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://www.england.nhs.uk/long-read/the-expectations-of-line-managers-in-relation-to-people-management/" target="_blank" rel="noopener">https://www.england.nhs.uk/long-read/the-expectations-of-line-managers-in-relation-to-people-management/</a></li>
</ul>
GUID, ],

            ['node_id' => 21, 'framework_variant_option_id' => 1, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://www.england.nhs.uk/long-read/the-expectations-of-line-managers-in-relation-to-people-management/" target="_blank" rel="noopener">https://www.england.nhs.uk/long-read/the-expectations-of-line-managers-in-relation-to-people-management/</a></li>
</ul>
GUID, ],
            ['node_id' => 21, 'framework_variant_option_id' => 2, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://www.england.nhs.uk/long-read/the-expectations-of-line-managers-in-relation-to-people-management/" target="_blank" rel="noopener">https://www.england.nhs.uk/long-read/the-expectations-of-line-managers-in-relation-to-people-management/</a></li>
</ul>
GUID, ],
            ['node_id' => 21, 'framework_variant_option_id' => 3, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://www.england.nhs.uk/long-read/the-expectations-of-line-managers-in-relation-to-people-management/" target="_blank" rel="noopener">https://www.england.nhs.uk/long-read/the-expectations-of-line-managers-in-relation-to-people-management/</a></li>
</ul>
GUID, ],
            ['node_id' => 21, 'framework_variant_option_id' => 4, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://www.england.nhs.uk/long-read/the-expectations-of-line-managers-in-relation-to-people-management/" target="_blank" rel="noopener">https://www.england.nhs.uk/long-read/the-expectations-of-line-managers-in-relation-to-people-management/</a></li>
</ul>
GUID, ],

            ['node_id' => 22, 'framework_variant_option_id' => 1, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_58335&programmeId=58335" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_58335&programmeId=58335</a></li>
</ul>
GUID, ],
            ['node_id' => 22, 'framework_variant_option_id' => 2, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_58335&programmeId=58335" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_58335&programmeId=58335</a></li>
</ul>
GUID, ],
            ['node_id' => 22, 'framework_variant_option_id' => 3, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_58335&programmeId=58335" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_58335&programmeId=58335</a></li>
</ul>
GUID, ],
            ['node_id' => 22, 'framework_variant_option_id' => 4, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_58335&programmeId=58335" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_58335&programmeId=58335</a></li>
</ul>
GUID, ],

            // Managing people and resources → Efficiency and effectiveness (node_ids: 24,25,26)
            ['node_id' => 24, 'framework_variant_option_id' => 1, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://www.hfma.org.uk/career-development/online-learning-library/funded-courses-nhs-staff" target="_blank" rel="noopener">https://www.hfma.org.uk/career-development/online-learning-library/funded-courses-nhs-staff</a></li>
</ul>
GUID, ],
            ['node_id' => 24, 'framework_variant_option_id' => 2, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://www.hfma.org.uk/career-development/online-learning-library/funded-courses-nhs-staff" target="_blank" rel="noopener">https://www.hfma.org.uk/career-development/online-learning-library/funded-courses-nhs-staff</a></li>
</ul>
GUID, ],
            ['node_id' => 24, 'framework_variant_option_id' => 3, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://www.hfma.org.uk/career-development/online-learning-library/funded-courses-nhs-staff" target="_blank" rel="noopener">https://www.hfma.org.uk/career-development/online-learning-library/funded-courses-nhs-staff</a></li>
</ul>
GUID, ],
            ['node_id' => 24, 'framework_variant_option_id' => 4, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://www.hfma.org.uk/career-development/online-learning-library/funded-courses-nhs-staff" target="_blank" rel="noopener">https://www.hfma.org.uk/career-development/online-learning-library/funded-courses-nhs-staff</a></li>
</ul>
GUID, ],

            ['node_id' => 25, 'framework_variant_option_id' => 1, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://www.hfma.org.uk/career-development/online-learning-library/funded-courses-nhs-staff" target="_blank" rel="noopener">https://www.hfma.org.uk/career-development/online-learning-library/funded-courses-nhs-staff</a></li>
</ul>
GUID, ],
            ['node_id' => 25, 'framework_variant_option_id' => 2, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://www.hfma.org.uk/career-development/online-learning-library/funded-courses-nhs-staff" target="_blank" rel="noopener">https://www.hfma.org.uk/career-development/online-learning-library/funded-courses-nhs-staff</a></li>
</ul>
GUID, ],
            ['node_id' => 25, 'framework_variant_option_id' => 3, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://www.hfma.org.uk/career-development/online-learning-library/funded-courses-nhs-staff" target="_blank" rel="noopener">https://www.hfma.org.uk/career-development/online-learning-library/funded-courses-nhs-staff</a></li>
</ul>
GUID, ],
            ['node_id' => 25, 'framework_variant_option_id' => 4, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://www.hfma.org.uk/career-development/online-learning-library/funded-courses-nhs-staff" target="_blank" rel="noopener">https://www.hfma.org.uk/career-development/online-learning-library/funded-courses-nhs-staff</a></li>
</ul>
GUID, ],

            ['node_id' => 26, 'framework_variant_option_id' => 1, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_40&programmeId=40" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_40&programmeId=40</a></li>
  <li><a href="https://www.england.nhs.uk/publication/making-data-count/" target="_blank" rel="noopener">https://www.england.nhs.uk/publication/making-data-count/</a></li>
</ul>
GUID, ],
            ['node_id' => 26, 'framework_variant_option_id' => 2, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_40&programmeId=40" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_40&programmeId=40</a></li>
  <li><a href="https://www.england.nhs.uk/publication/making-data-count/" target="_blank" rel="noopener">https://www.england.nhs.uk/publication/making-data-count/</a></li>
</ul>
GUID, ],
            ['node_id' => 26, 'framework_variant_option_id' => 3, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_40&programmeId=40" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_40&programmeId=40</a></li>
  <li><a href="https://www.england.nhs.uk/publication/making-data-count/" target="_blank" rel="noopener">https://www.england.nhs.uk/publication/making-data-count/</a></li>
</ul>
GUID, ],
            ['node_id' => 26, 'framework_variant_option_id' => 4, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_40&programmeId=40" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_40&programmeId=40</a></li>
  <li><a href="https://www.england.nhs.uk/publication/making-data-count/" target="_blank" rel="noopener">https://www.england.nhs.uk/publication/making-data-count/</a></li>
</ul>
GUID, ],

            // Delivering across health and care → Improving quality (node_ids: 29,30,31)
            ['node_id' => 29, 'framework_variant_option_id' => 1, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://portal.e-lfh.org.uk/Component/Details/700719" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Component/Details/700719</a></li>
</ul>
GUID, ],
            ['node_id' => 29, 'framework_variant_option_id' => 2, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://portal.e-lfh.org.uk/Component/Details/700721" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Component/Details/700721</a></li>
</ul>
GUID, ],
            ['node_id' => 29, 'framework_variant_option_id' => 3, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://portal.e-lfh.org.uk/Component/Details/700722" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Component/Details/700722</a></li>
</ul>
GUID, ],
            ['node_id' => 29, 'framework_variant_option_id' => 4, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://portal.e-lfh.org.uk/Component/Details/700723" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Component/Details/700723</a></li>
</ul>
GUID, ],

            ['node_id' => 30, 'framework_variant_option_id' => 1, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_35723&programmeId=35723" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_35723&programmeId=35723</a></li>
</ul>
GUID, ],
            ['node_id' => 30, 'framework_variant_option_id' => 2, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_35723&programmeId=35724" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_35723&programmeId=35724</a></li>
</ul>
GUID, ],
            ['node_id' => 30, 'framework_variant_option_id' => 3, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_35723&programmeId=35725" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_35723&programmeId=35725</a></li>
</ul>
GUID, ],
            ['node_id' => 30, 'framework_variant_option_id' => 4, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_35723&programmeId=35727" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_35723&programmeId=35727</a></li>
</ul>
GUID, ],

            ['node_id' => 31, 'framework_variant_option_id' => 1, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://nhsproviders.org/resources/a-guide-to-good-governance-in-the-nhs" target="_blank" rel="noopener">https://nhsproviders.org/resources/a-guide-to-good-governance-in-the-nhs</a></li>
</ul>
GUID, ],
            ['node_id' => 31, 'framework_variant_option_id' => 2, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://nhsproviders.org/resources/a-guide-to-good-governance-in-the-nhs" target="_blank" rel="noopener">https://nhsproviders.org/resources/a-guide-to-good-governance-in-the-nhs</a></li>
</ul>
GUID, ],
            ['node_id' => 31, 'framework_variant_option_id' => 3, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://nhsproviders.org/resources/a-guide-to-good-governance-in-the-nhs" target="_blank" rel="noopener">https://nhsproviders.org/resources/a-guide-to-good-governance-in-the-nhs</a></li>
</ul>
GUID, ],
            ['node_id' => 31, 'framework_variant_option_id' => 4, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://nhsproviders.org/resources/a-guide-to-good-governance-in-the-nhs" target="_blank" rel="noopener">https://nhsproviders.org/resources/a-guide-to-good-governance-in-the-nhs</a></li>
</ul>
GUID, ],

            // Delivering across health and care → Innovation and improvement (node_ids: 33,34,35)
            ['node_id' => 33, 'framework_variant_option_id' => 1, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://www.england.nhs.uk/nhsimpact/" target="_blank" rel="noopener">https://www.england.nhs.uk/nhsimpact/</a></li>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_123_8082&programmeId=123" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_123_8082&programmeId=123</a></li>
</ul>
GUID, ],
            ['node_id' => 33, 'framework_variant_option_id' => 2, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://www.england.nhs.uk/nhsimpact/" target="_blank" rel="noopener">https://www.england.nhs.uk/nhsimpact/</a></li>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_123_8082&programmeId=124" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_123_8082&programmeId=124</a></li>
</ul>
GUID, ],
            ['node_id' => 33, 'framework_variant_option_id' => 3, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://www.england.nhs.uk/nhsimpact/" target="_blank" rel="noopener">https://www.england.nhs.uk/nhsimpact/</a></li>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_123_8082&programmeId=125" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_123_8082&programmeId=125</a></li>
</ul>
GUID, ],
            ['node_id' => 33, 'framework_variant_option_id' => 4, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://www.england.nhs.uk/nhsimpact/" target="_blank" rel="noopener">https://www.england.nhs.uk/nhsimpact/</a></li>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_123_8082&programmeId=127" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_123_8082&programmeId=127</a></li>
</ul>
GUID, ],

            ['node_id' => 34, 'framework_variant_option_id' => 1, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://digital-transformation.hee.nhs.uk/digital-academy/programmes" target="_blank" rel="noopener">https://digital-transformation.hee.nhs.uk/digital-academy/programmes</a></li>
  <li><a href="https://www.hfma.org.uk/education-items/introduction-digital-transformation" target="_blank" rel="noopener">https://www.hfma.org.uk/education-items/introduction-digital-transformation</a></li>
  <li><a href="https://www.hfma.org.uk/education-items/making-case-investment-digital-technologies" target="_blank" rel="noopener">https://www.hfma.org.uk/education-items/making-case-investment-digital-technologies</a></li>
  <li><a href="https://www.hfma.org.uk/education-items/change-management-digital-making-it-happen" target="_blank" rel="noopener">https://www.hfma.org.uk/education-items/change-management-digital-making-it-happen</a></li>
</ul>
GUID, ],
            ['node_id' => 34, 'framework_variant_option_id' => 2, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://digital-transformation.hee.nhs.uk/digital-academy/programmes" target="_blank" rel="noopener">https://digital-transformation.hee.nhs.uk/digital-academy/programmes</a></li>
  <li><a href="https://www.hfma.org.uk/education-items/introduction-digital-transformation" target="_blank" rel="noopener">https://www.hfma.org.uk/education-items/introduction-digital-transformation</a></li>
  <li><a href="https://www.hfma.org.uk/education-items/making-case-investment-digital-technologies" target="_blank" rel="noopener">https://www.hfma.org.uk/education-items/making-case-investment-digital-technologies</a></li>
  <li><a href="https://www.hfma.org.uk/education-items/change-management-digital-making-it-happen" target="_blank" rel="noopener">https://www.hfma.org.uk/education-items/change-management-digital-making-it-happen</a></li>
</ul>
GUID, ],
            ['node_id' => 34, 'framework_variant_option_id' => 3, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://digital-transformation.hee.nhs.uk/digital-academy/programmes" target="_blank" rel="noopener">https://digital-transformation.hee.nhs.uk/digital-academy/programmes</a></li>
  <li><a href="https://www.hfma.org.uk/education-items/introduction-digital-transformation" target="_blank" rel="noopener">https://www.hfma.org.uk/education-items/introduction-digital-transformation</a></li>
  <li><a href="https://www.hfma.org.uk/education-items/making-case-investment-digital-technologies" target="_blank" rel="noopener">https://www.hfma.org.uk/education-items/making-case-investment-digital-technologies</a></li>
  <li><a href="https://www.hfma.org.uk/education-items/change-management-digital-making-it-happen" target="_blank" rel="noopener">https://www.hfma.org.uk/education-items/change-management-digital-making-it-happen</a></li>
</ul>
GUID, ],
            ['node_id' => 34, 'framework_variant_option_id' => 4, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://digital-transformation.hee.nhs.uk/digital-academy/programmes" target="_blank" rel="noopener">https://digital-transformation.hee.nhs.uk/digital-academy/programmes</a></li>
  <li><a href="https://www.hfma.org.uk/education-items/introduction-digital-transformation" target="_blank" rel="noopener">https://www.hfma.org.uk/education-items/introduction-digital-transformation</a></li>
  <li><a href="https://www.hfma.org.uk/education-items/making-case-investment-digital-technologies" target="_blank" rel="noopener">https://www.hfma.org.uk/education-items/making-case-investment-digital-technologies</a></li>
  <li><a href="https://www.hfma.org.uk/education-items/change-management-digital-making-it-happen" target="_blank" rel="noopener">https://www.hfma.org.uk/education-items/change-management-digital-making-it-happen</a></li>
</ul>
GUID, ],

            ['node_id' => 35, 'framework_variant_option_id' => 1, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_43876&programmeId=43876" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_43876&programmeId=43876</a></li>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_33501&programmeId=33501" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_33501&programmeId=33501</a></li>
</ul>
GUID, ],
            ['node_id' => 35, 'framework_variant_option_id' => 2, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_43876&programmeId=43876" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_43876&programmeId=43876</a></li>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_33501&programmeId=33502" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_33501&programmeId=33502</a></li>
</ul>
GUID, ],
            ['node_id' => 35, 'framework_variant_option_id' => 3, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_43876&programmeId=43876" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_43876&programmeId=43876</a></li>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_33501&programmeId=33504" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_33501&programmeId=33504</a></li>
</ul>
GUID, ],
            ['node_id' => 35, 'framework_variant_option_id' => 4, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_43876&programmeId=43876" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_43876&programmeId=43876</a></li>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_33501&programmeId=33505" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_33501&programmeId=33505</a></li>
</ul>
GUID, ],

            // Delivering across health and care → Working collaboratively (node_ids: 37,38,39)
            ['node_id' => 37, 'framework_variant_option_id' => 1, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_41710&programmeId=41710" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_41710&programmeId=41710</a></li>
</ul>
GUID, ],
            ['node_id' => 37, 'framework_variant_option_id' => 2, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_41710&programmeId=41711" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_41710&programmeId=41711</a></li>
</ul>
GUID, ],
            ['node_id' => 37, 'framework_variant_option_id' => 3, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_41710&programmeId=41712" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_41710&programmeId=41712</a></li>
</ul>
GUID, ],
            ['node_id' => 37, 'framework_variant_option_id' => 4, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_41710&programmeId=41714" target="_blank" rel="noopener">https://portal.e-lfh.org.uk/Catalogue/Index?HierarchyId=0_41710&programmeId=41714</a></li>
</ul>
GUID, ],

            ['node_id' => 38, 'framework_variant_option_id' => 1, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://www.leadershipacademy.nhs.uk/bitesize/short-courses/leadership-in-systems/" target="_blank" rel="noopener">https://www.leadershipacademy.nhs.uk/bitesize/short-courses/leadership-in-systems/</a></li>
  <li><a href="https://www.leadershipacademy.nhs.uk/bitesize/videos/developing-strategic-networks/" target="_blank" rel="noopener">https://www.leadershipacademy.nhs.uk/bitesize/videos/developing-strategic-networks/</a></li>
</ul>
GUID, ],
            ['node_id' => 38, 'framework_variant_option_id' => 2, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://www.leadershipacademy.nhs.uk/bitesize/short-courses/leadership-in-systems/" target="_blank" rel="noopener">https://www.leadershipacademy.nhs.uk/bitesize/short-courses/leadership-in-systems/</a></li>
  <li><a href="https://www.leadershipacademy.nhs.uk/bitesize/videos/developing-strategic-networks/" target="_blank" rel="noopener">https://www.leadershipacademy.nhs.uk/bitesize/videos/developing-strategic-networks/</a></li>
</ul>
GUID, ],
            ['node_id' => 38, 'framework_variant_option_id' => 3, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://www.leadershipacademy.nhs.uk/bitesize/short-courses/leadership-in-systems/" target="_blank" rel="noopener">https://www.leadershipacademy.nhs.uk/bitesize/short-courses/leadership-in-systems/</a></li>
  <li><a href="https://www.leadershipacademy.nhs.uk/bitesize/videos/developing-strategic-networks/" target="_blank" rel="noopener">https://www.leadershipacademy.nhs.uk/bitesize/videos/developing-strategic-networks/</a></li>
</ul>
GUID, ],
            ['node_id' => 38, 'framework_variant_option_id' => 4, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://www.leadershipacademy.nhs.uk/bitesize/short-courses/leadership-in-systems/" target="_blank" rel="noopener">https://www.leadershipacademy.nhs.uk/bitesize/short-courses/leadership-in-systems/</a></li>
  <li><a href="https://www.leadershipacademy.nhs.uk/bitesize/videos/developing-strategic-networks/" target="_blank" rel="noopener">https://www.leadershipacademy.nhs.uk/bitesize/videos/developing-strategic-networks/</a></li>
</ul>
GUID, ],

            ['node_id' => 39, 'framework_variant_option_id' => 1, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://www.england.nhs.uk/improvement-hub/wp-content/uploads/sites/44/2015/08/Learning-Handbook.pdf" target="_blank" rel="noopener">https://www.england.nhs.uk/improvement-hub/wp-content/uploads/sites/44/2015/08/Learning-Handbook.pdf</a></li>
  <li><a href="https://www.nhsprofessionals.nhs.uk/campaigns/-/media/25be7627b92643ea93cf1f1c852fbfd5" target="_blank" rel="noopener">https://www.nhsprofessionals.nhs.uk/campaigns/-/media/25be7627b92643ea93cf1f1c852fbfd5</a></li>
</ul>
GUID, ],
            ['node_id' => 39, 'framework_variant_option_id' => 2, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://www.england.nhs.uk/improvement-hub/wp-content/uploads/sites/44/2015/08/Learning-Handbook.pdf" target="_blank" rel="noopener">https://www.england.nhs.uk/improvement-hub/wp-content/uploads/sites/44/2015/08/Learning-Handbook.pdf</a></li>
  <li><a href="https://www.nhsprofessionals.nhs.uk/campaigns/-/media/25be7627b92643ea93cf1f1c852fbfd6" target="_blank" rel="noopener">https://www.nhsprofessionals.nhs.uk/campaigns/-/media/25be7627b92643ea93cf1f1c852fbfd6</a></li>
</ul>
GUID, ],
            ['node_id' => 39, 'framework_variant_option_id' => 3, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://www.england.nhs.uk/improvement-hub/wp-content/uploads/sites/44/2015/08/Learning-Handbook.pdf" target="_blank" rel="noopener">https://www.england.nhs.uk/improvement-hub/wp-content/uploads/sites/44/2015/08/Learning-Handbook.pdf</a></li>
  <li><a href="https://www.nhsprofessionals.nhs.uk/campaigns/-/media/25be7627b92643ea93cf1f1c852fbfd7" target="_blank" rel="noopener">https://www.nhsprofessionals.nhs.uk/campaigns/-/media/25be7627b92643ea93cf1f1c852fbfd7</a></li>
</ul>
GUID, ],
            ['node_id' => 39, 'framework_variant_option_id' => 4, 'min_value' => 0, 'max_value' => 3, 'guidance' => <<<'GUID'
<ul>
  <li><a href="https://www.england.nhs.uk/improvement-hub/wp-content/uploads/sites/44/2015/08/Learning-Handbook.pdf" target="_blank" rel="noopener">https://www.england.nhs.uk/improvement-hub/wp-content/uploads/sites/44/2015/08/Learning-Handbook.pdf</a></li>
  <li><a href="https://www.nhsprofessionals.nhs.uk/campaigns/-/media/25be7627b92643ea93cf1f1c852fbfd9" target="_blank" rel="noopener">https://www.nhsprofessionals.nhs.uk/campaigns/-/media/25be7627b92643ea93cf1f1c852fbfd9</a></li>
</ul>
GUID, ],
        ];

        foreach ($signposts as $s) {
            Signpost::firstOrCreate([
                'node_id' => $s['node_id'],
                'framework_variant_option_id' => $s['framework_variant_option_id'],
                'min_value' => $s['min_value'],
                'max_value' => $s['max_value'],
            ], [
                'guidance' => $s['guidance'],
            ]);
        }
    }
}