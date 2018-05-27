<?php
namespace DigitalPenguin\Referrals\Admin\Referral;

use modmore\Commerce\Admin\Widgets\GridWidget;

class ReferralGrid extends GridWidget {
    public $key = 'referrals-grid';
    public $defaultSort = 'referred_on';
    public $defaultSortDir = 'DESC';

    public function getItems(array $options = array())
    {
        $items = [];

        $c = $this->adapter->newQuery('CommerceReferralsReferral');
        $c->leftJoin('CommerceReferralsReferrer','Referrer','CommerceReferralsReferral.referrer_id=Referrer.id');
        $count = $this->adapter->getCount('CommerceReferralsReferral', $c);
        $this->setTotalCount($count);
        $c->sortby($this->defaultSort,$this->defaultSortDir);
        $c->limit($options['limit'], $options['start']);
        $c->select('CommerceReferralsReferral.*');
        $c->select($this->adapter->getSelectColumns('CommerceReferralsReferrer','Referrer',[
            'name'
        ]));

        if ($options['search_by_referrer']) {
            $c->where([
                'Referrer.name:LIKE' => '%' . $options['search_by_referrer'] . '%'

            ]);
        }
        //$c->prepare();
        //$this->adapter->log(1,$c->toSQL());
        $collection = $this->adapter->getCollection('CommerceReferralsReferral', $c);

        foreach ($collection as $object) {
            $items[] = $this->prepareItem($object->toArray());
        }

        return $items;
    }

    public function getColumns(array $options = array())
    {
        return [
            /*[
                'name' => 'id',
                'title' => 'ID',
                'primary' => true,
                'sortable'=>true,
            ],*/
            [
                'name' => 'referrer',
                'title' => $this->adapter->lexicon('commerce_referrals.referral.referrer'),
                'sortable' => true,
            ],
            [
                'name' => 'referred_on',
                'title' => $this->adapter->lexicon('commerce_referrals.referral.when'),
                'sortable' => true,
            ],
            [
                'name' => 'customer',
                'title' => $this->adapter->lexicon('commerce_referrals.referral.customer'),
                'sortable' => true,
            ],
            [
                'name' => 'amount',
                'title' => $this->adapter->lexicon('commerce_referrals.referral.amount'),
                'sortable' => true,
            ],
            [
                'name' => 'order',
                'title' => $this->adapter->lexicon('commerce_referrals.referral.order'),
                'sortable' => true,
            ],
        ];
    }

    public function getTopToolbar(array $options = array())
    {
        $toolbar = [];

        $toolbar[] = [
            'name' => 'search_by_referrer',
            'title' => $this->adapter->lexicon('commerce_referrals.search_by_referrer'),
            'type' => 'textfield',
            'value' => array_key_exists('search_by_referrer', $options) ? (int)$options['search_by_referrer'] : '',
            'position' => 'top',
            'width' => 'four wide',
        ];


        $toolbar[] = [
            'name' => 'limit',
            'title' => $this->adapter->lexicon('commerce.limit'),
            'type' => 'textfield',
            'value' => (int)$options['limit'],
            'position' => 'bottom',
            'width' => 'two wide',
        ];

        return $toolbar;
    }

    public function prepareItem($item)
    {
        $item['actions'] = [];

        if($item['order']) {
            $orderId = $item['order'];
            $order = $this->adapter->getObject('comOrder',[
                'id'    =>  $orderId
            ]);
            $order = $order->toArray();
            $item['amount'] = $order['total_formatted'];
            $item['order'] = '<a href="?namespace=commerce&a=index&ca=order&order='.$item['order'].'">'.$this->adapter->lexicon('commerce_referrals.referral.view_order_details').'</a>';

            $c = $this->adapter->newQuery('comOrderAddress');
            $c->leftJoin('comAddress','Address','Address.id=comOrderAddress.address');
            $c->where([
                'order' => $order['id']
            ]);
            $c->select('comOrderAddress.id,Address.fullname');
            $address = $this->adapter->getObject('comOrderAddress',$c);
            $item['customer'] = $address->get('fullname');
        }



        if($item['referred_on']) {
            $item['referred_on'] = date('H:i A - dS M Y', $item['referred_on']);
        }

        if($item['referrer_id']) {
            $referrer = $this->adapter->getObject('CommerceReferralsReferrer',[
                'id'    => $item['referrer_id']
            ]);
            if($referrer) {
                $item['referrer'] = $referrer->get('name');
            }
        }


        /*
        $editUrl = $this->adapter->makeAdminUrl('referrals/update', ['id' => $item['id']]);
        $item['actions'][] = (new Action())
            ->setUrl($editUrl)
            ->setTitle($this->adapter->lexicon('commerce_referrals.referral.edit'))
            ->setIcon('icon-edit');
        $deleteUrl = $this->adapter->makeAdminUrl('referrals/delete', ['id' => $item['id']]);
        $item['actions'][] = (new Action())
            ->setUrl($deleteUrl)
            ->setTitle($this->adapter->lexicon('commerce_referrals.referral.delete'))
            ->setIcon('icon-trash');
        */


        return $item;
    }
}