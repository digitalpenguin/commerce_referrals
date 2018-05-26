<?php
$xpdo_meta_map['CommerceReferralsReferral']= array (
  'package' => 'commerce_referrals',
  'version' => '1.1',
  'table' => 'commerce_referrals_referrals',
  'extends' => 'comSimpleObject',
  'tableMeta' => 
  array (
    'engine' => 'InnoDB',
  ),
  'fields' => 
  array (
    'referrer_id' => NULL,
    'order' => NULL,
    'referred_on' => 0,
  ),
  'fieldMeta' => 
  array (
    'referrer_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
    ),
    'order' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => true,
    ),
    'referred_on' => 
    array (
      'formatter' => 'datetime',
      'dbtype' => 'int',
      'precision' => '20',
      'phptype' => 'int',
      'null' => false,
      'default' => 0,
    ),
  ),
);
