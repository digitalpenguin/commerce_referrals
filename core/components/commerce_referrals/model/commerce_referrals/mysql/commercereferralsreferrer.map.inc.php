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

$xpdo_meta_map['CommerceReferralsReferrer']= array (
  'package' => 'commerce_referrals',
  'version' => '1.1',
  'table' => 'commerce_referrals_referrers',
  'extends' => 'xPDOSimpleObject',
  'tableMeta' => 
  array (
    'engine' => 'InnoDB',
  ),
  'fields' => 
  array (
    'name' => '',
    'token' => NULL,
    'contact_person' => '',
    'email' => NULL,
    'phone' => NULL,
    'website' => NULL,
    'address1' => NULL,
    'address2' => NULL,
    'country' => '',
    'comment' => NULL,
    'added_on' => 0,
    'position' => 0,
  ),
  'fieldMeta' => 
  array (
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '190',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'token' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '40',
      'phptype' => 'string',
      'null' => false,
    ),
    'contact_person' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '190',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'email' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '190',
      'phptype' => 'string',
      'null' => true,
    ),
    'phone' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '190',
      'phptype' => 'string',
      'null' => true,
    ),
    'website' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '190',
      'phptype' => 'string',
      'null' => true,
    ),
    'address1' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '190',
      'phptype' => 'string',
      'null' => true,
    ),
    'address2' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '190',
      'phptype' => 'string',
      'null' => true,
    ),
    'country' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '20',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'comment' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ),
    'added_on' => 
    array (
      'formatter' => 'datetime',
      'dbtype' => 'int',
      'precision' => '20',
      'phptype' => 'int',
      'null' => false,
      'default' => 0,
    ),
    'position' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
  ),
  'composites' => 
  array (
    'Referral' => 
    array (
      'class' => 'CommerceReferralsReferral',
      'local' => 'id',
      'foreign' => 'referrer_id',
      'cardinality' => 'one',
      'owner' => 'local',
    ),
  ),
);
