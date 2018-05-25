<?php
/**
 * Referrals for Commerce.
 *
 * Copyright 2018 by Your Name <your@email.com>
 *
 * This file is meant to be used with Commerce by modmore. A valid Commerce license is required.
 *
 * @package commerce_referrals
 * @license See core/components/commerce_referrals/docs/license.txt
 */

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
    'product_id' => NULL,
    'referred_url' => '',
    'referred_name' => '',
    'description' => NULL,
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
    'product_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
    ),
    'referred_url' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '190',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'referred_name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '190',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'description' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
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
