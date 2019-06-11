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
 * @copyright   Copyright (c) Blackbird (https://black.bird.eu)
 * @author      Blackbird Team
 * @license     MIT
 * @support     https://github.com/blackbird-agency/magento-2-orange-sms-notification/issues/new
 */
declare(strict_types=1);

namespace Blackbird\OrangeSmsNotification\Service;

use Blackbird\OrangeSms\Api\SmsBuilderInterface;
use Blackbird\OrangeSms\Api\SmsManagementInterface;
use Blackbird\OrangeSms\Exception\OrangeSmsSendException;
use Blackbird\PhoneNumberLib\Parser\PhoneNumberParser;
use Blackbird\SmsNotification\Exception\SendNotificationException;
use Blackbird\SmsNotification\Model\Notification\Adapter\AdapterInterface;
use Blackbird\SmsNotification\Model\Notification\MessageInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Phrase;
use Magento\Store\Model\ScopeInterface;
use Psr\Log\LoggerInterface;

/**
 * Class OrangeSmsAdapter, connector to SmsNotification adapters
 * @api
 */
final class OrangeSmsAdapter implements AdapterInterface
{
    public const CODE = 'orange_sms';

    /**#@+
     * Orange Sms Notifications General Config Paths
     */
    private const CONFIG_PATH_ORANGE_SMS_ENABLED = 'orange_sms/general/enabled';
    /**#@-*/

    /**
     * @var \Blackbird\OrangeSms\Api\SmsBuilderInterface
     */
    private $smsBuilder;

    /**
     * @var \Blackbird\OrangeSms\Api\SmsManagementInterface
     */
    private $smsManagement;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var \Blackbird\PhoneNumberLib\Parser\PhoneNumberParser
     */
    private $phoneNumberParser;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Blackbird\OrangeSms\Api\SmsBuilderInterface $smsBuilder
     * @param \Blackbird\OrangeSms\Api\SmsManagementInterface $smsManagement
     * @param \Blackbird\PhoneNumberLib\Parser\PhoneNumberParser $phoneNumberParser
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        SmsBuilderInterface $smsBuilder,
        SmsManagementInterface $smsManagement,
        PhoneNumberParser $phoneNumberParser,
        LoggerInterface $logger
    ) {
        $this->smsBuilder = $smsBuilder;
        $this->smsManagement = $smsManagement;
        $this->scopeConfig = $scopeConfig;
        $this->phoneNumberParser = $phoneNumberParser;
        $this->logger = $logger;
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
        return $this->scopeConfig->isSetFlag(self::CONFIG_PATH_ORANGE_SMS_ENABLED, ScopeInterface::SCOPE_STORE);
    }

    /**
     * {@inheritdoc}
     */
    public function sendMessage(MessageInterface $message): bool
    {
        try {
            $this->smsBuilder->setTo($this->phoneNumberParser->parse($message->getTo()));
            $this->smsBuilder->setFrom($this->phoneNumberParser->parse($message->getFrom()));
            $this->smsBuilder->setMessage($message->getText());
            /** @var \Blackbird\OrangeSms\Api\Data\SmsInterface $sms */
            $sms = $this->smsBuilder->create();

            return $this->smsManagement->send($sms);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
        }

        return false;
    }
}
