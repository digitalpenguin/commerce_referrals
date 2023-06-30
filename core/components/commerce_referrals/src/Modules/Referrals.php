<?php
namespace DigitalPenguin\Referrals\Modules;

use modmore\Commerce\Events\Admin\GeneratorEvent;
use modmore\Commerce\Events\Admin\OrderActions;
use modmore\Commerce\Events\Admin\TopNavMenu as TopNavMenuEvent;
use modmore\Commerce\Events\OrderState;
use modmore\Commerce\Modules\BaseModule;
use modmore\Commerce\Dispatcher\EventDispatcher;
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
    public function addReferrerTokenToOrder(OrderState $event)
    {
        $order = $event->getOrder();
        if ($order) {
            $orderArr = $order->toArray();
            if (!empty($_SESSION['commerce_referrals_reference'])) {
                $token = $_SESSION['commerce_referrals_reference'];
                $referrer = $this->adapter->getObject(\CommerceReferralsReferrer::class,[
                    'token' =>  $token,
                ]);

                if ($referrer) {
                    $order->setProperty('referrer', $token);
                    $order->save();
                    // Create referral record
                    $referral = $this->adapter->newObject(\CommerceReferralsReferral::class);
                    $referral->set('referrer_id', $referrer->get('id'));
                    $referral->set('referred_on', time());
                    $referral->set('order', $orderArr['id']);
                    $referral->save();
                }
            }
        }
    }

    /**
     * Loads the order from the event.
     * @param OrderActions $event
     */
    public function getOrder(OrderActions $event)
    {
        $this->order = $event->getOrder();
    }

    /**
     * Adds the referrer section to the order page. This allows the user to see which partner
     * referred the customer that made the purchase.
     * @param PageEvent $event
     */
    public function addSectionToOrderPage(PageEvent $event)
    {
        $page = $event->getPage();
        $meta = $page->getMeta();
        if ($meta['key'] === 'order') {
            $refArray = $this->order->getProperty('referrer');
            if ($refArray['token']) {
                $referrer = $this->adapter->getObject(\CommerceReferralsReferrer::class, [
                    'token' =>  $refArray['token']
                ]);
                if ($referrer) {
                    $page->addSection((new ReferralSection($this->commerce, [
                        'order' => $this->order,
                        'priority' => 6,
                        'referrer' => $referrer->toArray()
                    ]))->setUp());
                }
            }
        }
    }

    /**
     * Loads required pages into the generator.
     * @param GeneratorEvent $event
     */
    public function loadPages(GeneratorEvent $event)
    {
        $generator = $event->getGenerator();
        $generator->addPage('referrals', '\DigitalPenguin\Referrals\Admin\Referral\ReferralPage');
        $generator->addPage('referrals/referrers', '\DigitalPenguin\Referrals\Admin\Referrer\ReferrerPage');
        $generator->addPage('referrers/create', '\DigitalPenguin\Referrals\Admin\Referrer\Create');
        $generator->addPage('referrers/update', '\DigitalPenguin\Referrals\Admin\Referrer\Update');
        $generator->addPage('referrers/delete', '\DigitalPenguin\Referrals\Admin\Referrer\Delete');
    }

    /**
     * Loads Referral tab into main nav as well as sub-nav tabs.
     * @param TopNavMenuEvent $event
     */
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
        ]], $this->adapter->getOption('commerce_referrals.tab_position', null, 3));
        $event->setItems($items);
    }

    /**
     * Helper function for adding an item into the specified position in a given array.
     * Primarily used for adding a tab into the main nav.
     * @param $array
     * @param $values
     * @param $offset
     * @return array
     */
    private function insertInArray($array, $values, $offset)
    {
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
