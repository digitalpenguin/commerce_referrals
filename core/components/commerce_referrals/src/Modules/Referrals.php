<?php
namespace DigitalPenguin\Referrals\Modules;

use modmore\Commerce\Events\Admin\GeneratorEvent;
use modmore\Commerce\Events\Admin\OrderActions;
use modmore\Commerce\Events\Admin\TopNavMenu as TopNavMenuEvent;
use modmore\Commerce\Events\OrderState;
use modmore\Commerce\Modules\BaseModule;
use Symfony\Component\EventDispatcher\EventDispatcher;
use modmore\Commerce\Events\Admin\PageEvent;
use modmore\Commerce\Events\Cart\Item;
use DigitalPenguin\Referrals\Admin\Order\ReferralSection;

require_once dirname(dirname(__DIR__)) . '/vendor/autoload.php';

class Referrals extends BaseModule {

    protected $order;

    public function getName()
    {
        $this->adapter->loadLexicon('commerce_referrals:default');
        return $this->adapter->lexicon('commerce_referrals');
    }

    public function getAuthor()
    {
        return 'Murray Wood - Digital Penguin';
    }

    public function getDescription()
    {
        return $this->adapter->lexicon('commerce_referrals.description');
    }

    public function initialize(EventDispatcher $dispatcher)
    {
        // Load our lexicon
        $this->adapter->loadLexicon('commerce_referrals:default');

        // Add the xPDO package, so Commerce can detect the derivative classes
        $root = dirname(dirname(__DIR__));
        $path = $root . '/model/';
        $this->adapter->loadPackage('commerce_referrals', $path);

        // Add template path to twig
//        /** @var ChainLoader $loader */
//        $root = dirname(dirname(__DIR__));
//        $loader = $this->commerce->twig->getLoader();
//        $loader->addLoader(new FilesystemLoader($root . '/templates/'));

        $dispatcher->addListener(\Commerce::EVENT_DASHBOARD_INIT_GENERATOR, [$this, 'loadPages']);
        $dispatcher->addListener(\Commerce::EVENT_DASHBOARD_GET_MENU, [$this, 'loadMenuItem']);
        $dispatcher->addListener(\Commerce::EVENT_DASHBOARD_PAGE_BEFORE_GENERATE,[$this, 'addSectionToOrderPage']);
        $dispatcher->addListener(\Commerce::EVENT_DASHBOARD_ORDER_ACTIONS,[$this, 'getOrder']);
        $dispatcher->addListener(\Commerce::EVENT_STATE_CART_TO_PROCESSING,[$this, 'addReferrerTokenToOrder']);

    }


    /**
     * Takes the 'ref' value from the user's session and
     * adds it to the order properties.
     * @param Item $event
     */
    public function addReferrerTokenToOrder(OrderState $event) {
        //$this->adapter->log(1,'Session ref: '.$_SESSION['ref']);
        $order = $event->getOrder();
        if($order){
            $orderArr = $order->toArray();
            if($_SESSION['ref']) {
                $referrerToken['token'] = $_SESSION['ref'];
                $referrer = $this->adapter->getObject('CommerceReferralsReferrer',[
                    'token' =>  $referrerToken['token']
                ]);

                if($referrer) {
                    $order->setProperty('referrer',$referrerToken);
                    $order->save();
                    // Create referral record
                    $referral = $this->adapter->newObject('CommerceReferralsReferral');
                    $referral->set('referrer_id',$referrer->get('id'));
                    $referral->set('referred_on',time());
                    $referral->set('order',$orderArr['id']);
                    $referral->save();
                }
            }
        }
    }


    public function getOrder(OrderActions $event) {
        $this->order = $event->getOrder();
    }

    /**
     * Adds the referrer section to the order page. This allows the user to see which partner
     * referred the customer that made the purchase.
     * @param PageEvent $event
     *
     * Section keys are:
     * - order_header
     * - referrer_section
     * - items_section
     * - customer_section
     * - shipments_section
     * - transactions_section
     */
    public function addSectionToOrderPage(PageEvent $event) {
        $page = $event->getPage();
        $meta = $page->getMeta();
        if($meta['key'] === 'order') {
            $orderArray = $this->order->toArray();
            $this->adapter->log(1,print_r($orderArray,true));

            if($orderArray['properties']['referrer']['token']) {
                //$this->adapter->log(1,print_r($orderArray,true));
                $referrer = $this->adapter->getObject('CommerceReferralsReferrer',[
                    'token' =>  $orderArray['properties']['referrer']['token']
                ]);
                if($referrer) {
                    $page->addSection((new ReferralSection($this->commerce, [
                        'order' => $this->order,
                        'priority' => 1,
                        'referrer' => $referrer->toArray()
                    ]))->setUp());
                    $page->findSection('items_section')->priority = 2;
                    $page->findSection('customer_section')->priority = 3;
                    $page->findSection('shipments_section')->priority = 4;
                    $page->findSection('transactions_section')->priority = 5;
                }
            }

        }
    }

    public function loadPages(GeneratorEvent $event)
    {
        $generator = $event->getGenerator();
        $generator->addPage('referrals', '\DigitalPenguin\Referrals\Admin\Referral\ReferralPage');
        $generator->addPage('referrals/referrers', '\DigitalPenguin\Referrals\Admin\Referrer\ReferrerPage');
        $generator->addPage('referrers/create', '\DigitalPenguin\Referrals\Admin\Referrer\Create');
        $generator->addPage('referrers/update', '\DigitalPenguin\Referrals\Admin\Referrer\Update');
        $generator->addPage('referrers/delete', '\DigitalPenguin\Referrals\Admin\Referrer\Delete');


    }

    public function loadMenuItem(TopNavMenuEvent $event)
    {
        $items = $event->getItems();

        $items = $this->insertInArray($items, ['referrals' => [
            'name' => 'Referrals',
            'key' => 'referrals',
            'icon' => 'icon comments outline',
            'link' => $this->adapter->makeAdminUrl('referrals'),
            'submenu' => [
                [
                    'name' => $this->adapter->lexicon('commerce_referrals.referrals'),
                    'key' => 'referrals',
                    'link' => $this->adapter->makeAdminUrl('referrals'),
                    'icon' => 'icon comments outline',
                ],
                [
                    'name' => $this->adapter->lexicon('commerce_referrals.referrers'),
                    'key' => 'referrals/referrers',
                    'link' => $this->adapter->makeAdminUrl('referrals/referrers'),
                    'icon' => 'icon icon-user',
                ],

            ],
        ]], 5);

        $event->setItems($items);
    }

    private function insertInArray($array,$values,$offset) {
        return array_slice($array, 0, $offset, true) + $values + array_slice($array, $offset, NULL, true);
    }

    public function getModuleConfiguration(\comModule $module)
    {
        $fields = [];

//        $fields[] = new DescriptionField($this->commerce, [
//            'description' => $this->adapter->lexicon('commerce_referrals.module_description'),
//        ]);

        return $fields;
    }



}
