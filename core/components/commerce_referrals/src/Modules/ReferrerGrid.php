<?php
namespace DigitalPenguin\Referrals\Modules;

use modmore\Commerce\Admin\Widgets\GridWidget;

class ReferrerGrid extends GridWidget {
    public $key = 'referrers_grid';
    public $title = '';
    public $defaultSort = 'name';

    public function getItems(array $options = array())
    {
        $items = [];

        /*$c = $this->adapter->newQuery('modUser');
        $c->innerJoin('modUserProfile', 'Profile');
        $c->select($this->adapter->getSelectColumns('modUser', 'modUser', '', ['password', 'cachepwd', 'hash_class', 'salt', 'session_stale'], true));
        $c->select($this->adapter->getSelectColumns('modUserProfile', 'Profile', 'profile_', ['sessionid'], true));

        $sortby = array_key_exists('sortby', $options) && !empty($options['sortby']) ? $this->adapter->escape($options['sortby']) : $this->defaultSort;
        $sortdir = array_key_exists('sortdir', $options) && strtoupper($options['sortdir']) === 'DESC' ? 'DESC' : 'ASC';
        $c->sortby($sortby, $sortdir);

        $count = $this->adapter->getCount('modUser', $c);
        $this->setTotalCount($count);

        $c->limit($options['limit'], $options['start']);

        $collection = $this->adapter->getCollection('modUser', $c);

        foreach ($collection as $object) {
            $items[] = $this->prepareItem($object);
        }*/

        return $items;
    }

    public function getColumns(array $options = array())
    {
        return [
            [
                'name' => 'id',
                'primary' => true,
                'hidden' => true,
            ],
            [
                'name' => 'name',
                'title' => $this->adapter->lexicon('commerce_referrals.name'),
                'sortable' => true,
            ],
            [
                'name' => 'token',
                'title' => $this->adapter->lexicon('commerce_referrals.token'),
                'sortable' => true,
            ],
            [
                'name' => 'email',
                'title' => $this->adapter->lexicon('commerce_referrals.email'),
                'sortable' => true,
            ],
            [
                'name' => 'phone',
                'title' => $this->adapter->lexicon('commerce_referrals.phone'),
                'sortable' => true,
            ],
            [
                'name' => 'referrals',
                'title' => $this->adapter->lexicon('commerce_referrals.referrals'),
                'sortable' => true,
            ],
            [
                'name' => 'latest',
                'title' => $this->adapter->lexicon('commerce_referrals.latest'),
                'sortable' => true,
            ],

        ];
    }

    public function getTopToolbar(array $options = array())
    {
        $toolbar = [];

//        $toolbar[] = [
//            'name' => 'add-coupon',
//            'title' => $this->adapter->lexicon('commerce.add_coupon'),
//            'type' => 'button',
//            'link' => $this->adapter->makeAdminUrl('coupons/create'),
//            'button_class' => 'commerce-ajax-modal',
//            'icon_class' => 'icon-plus',
//            'modal_title' => $this->adapter->lexicon('commerce.add_coupon'),
//            'position' => 'top',
//        ];

        $toolbar[] = [
            'name' => 'limit',
            'title' => $this->adapter->lexicon('commerce.limit'),
            'type' => 'textfield',
            'value' => (int)$options['limit'],
            'position' => 'bottom',
            'width' => 'four wide',
        ];

        return $toolbar;
    }

    public function prepareItem(\modUser $user)
    {
        $item = $user->toArray('', false, true);

        $item['username'] = $item['username'] . ' (#' . $item['id'] . ')';
        $editUserLink = $this->adapter->getOption('manager_url') . '?a=security/user/update&id=' . $item['id'];
        $item['username'] = '<a href="' . $editUserLink . '" target="_blank">' . $item['username'] . '</a>';
        $item['fullname'] = $item['profile_fullname'];
        $item['email'] = $item['profile_email'];

        $item['order_count'] = $this->adapter->getCount('comOrder', ['user' => $item['id'], 'test' => $this->commerce->isTestMode()]);

        // Calculate the order total
        $item['order_total'] = 0;
        $c = $this->adapter->newQuery('comOrder');
        $c->where([
            'user' => $item['id'],
            'AND:class_key:!=' => 'comCartOrder',
            'AND:comOrder.test:=' => $this->commerce->isTestMode(),
        ]);
        $c->select($this->adapter->getSelectColumns('comOrder', 'comOrder', '', ['id', 'user', 'class_key']));
        $c->select('SUM(comOrder.total) as order_total');
        $c->groupby('comOrder.user');

        $object = $this->adapter->getObject('comOrder', $c);
        if ($object instanceof \comOrder) {
            $item['order_total'] = $object->get('order_total');
        }
        $item['order_total'] = $this->commerce->formatValue($item['order_total'], 'financial');

        // Define the actions for the item
        $item['actions'] = [];
        $item['actions'][] = [
            'url' => $editUserLink,
            'title' => $this->adapter->lexicon('commerce.module.customers.edit_user'),
            'target' => 'blank',
            'icon' => 'icon-edit',
        ];

        // @todo add something to browse customer orders

        return $item;
    }
}