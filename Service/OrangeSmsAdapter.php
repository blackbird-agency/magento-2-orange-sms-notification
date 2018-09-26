<?php
/**
 * Blackbird Orange SMS Notification Module
 *
 * NOTICE OF LICENSE
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@bird.eu so we can send you a copy immediately.
 *
 * @category    Blackbird
 * @package     Blackbird_OrangeSmsNotification
 * @copyright   Copyright (c) 2018 Blackbird (https://black.bird.eu)
 * @author      Blackbird Team
 * @license     https://store.bird.eu/license/
 * @support     help@bird.eu
 */
declare(strict_types=1);

namespace Blackbird\OrangeSmsNotification\Service;

use Blackbird\OrangeSms\Api\Data\SmsInterfaceFactory;
use Blackbird\OrangeSms\Api\SmsManagementInterface;
use Blackbird\OrangeSms\Exception\OrangeSmsSendException;
use Blackbird\SmsNotification\Exception\SendNotificationException;
use Blackbird\SmsNotification\Model\Notification\Adapter\AdapterInterface;
use Blackbird\SmsNotification\Model\Notification\MessageInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Phrase;
use Magento\Store\Model\ScopeInterface;

/**
 * Class OrangeSmsAdapter, connector to SmsNotification adapters
 */
class OrangeSmsAdapter implements AdapterInterface
{
    const CODE = 'orange_sms';

    /**#@+
     * Orange Sms Notifications General Config Paths
     */
    const XML_PATH_ORANGE_SMS_ENABLED = 'orange_sms/general/enabled';
    /**#@-*/

    /**
     * @var \Blackbird\OrangeSms\Api\Data\SmsInterfaceFactory
     */
    private $smsFactory;

    /**
     * @var \Blackbird\OrangeSms\Api\SmsManagementInterface
     */
    private $smsManagement;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Blackbird\OrangeSms\Api\Data\SmsInterfaceFactory $smsFactory
     * @param \Blackbird\OrangeSms\Api\SmsManagementInterface $smsManagement
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        SmsInterfaceFactory $smsFactory,
        SmsManagementInterface $smsManagement
    ) {
        $this->smsFactory = $smsFactory;
        $this->smsManagement = $smsManagement;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function getCode(): string
    {
        return self::CODE;
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel(): string
    {
        return (new Phrase('Orange SMS'))->render();
    }

    /**
     * {@inheritdoc}
     */
    public function isEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_ORANGE_SMS_ENABLED, ScopeInterface::SCOPE_STORE);
    }

    /**
     * {@inheritdoc}
     */
    public function sendMessage(MessageInterface $message): bool
    {
        /** @var \Blackbird\OrangeSms\Api\Data\SmsInterface $sms */
        $sms = $this->smsFactory->create();
        $sms->setTo($message->getTo());
        $sms->setFrom($message->getFrom());
        $sms->setMessage($message->getText());

        try {
            $this->smsManagement->send($sms);
        } catch (OrangeSmsSendException $e) {
            throw new SendNotificationException(new Phrase('Impossible to send the message.'), $e);
        }

        return true;
    }
}
