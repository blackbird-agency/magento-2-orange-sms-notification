# Orange Sms Notification

[![Latest Stable Version](https://img.shields.io/packagist/v/blackbird/module-orange-sms-notification.svg?style=flat-square)](https://packagist.org/packages/blackbird/module-orange-sms-notification)
[![License: MIT](https://img.shields.io/github/license/blackbird-agency/magento-2-orange-sms-notification.svg?style=flat-square)](./LICENSE)  

This module is a free connector for the SmsNotification and the OrangeSms modules.  
The free source is available at the [github repository](https://github.com/blackbird-agency/magento-2-orange-sms-notification).

These modules are available on our [store](https://store.bird.eu/).

## Setup

### Get the package

**Zip Package:** *(not recommended)*

Unzip the package in app/code/Blackbird/OrangeSmsNotification, from the root of your Magento instance.

**Composer Package:** *(recommended)*

```
composer require blackbird/module-orange-sms-notification
```

### Install the module

Go to your Magento root, then run the following magento command:

```
php bin/magento setup:upgrade
```

**If you are in production mode, do not forget to recompile and redeploy the static resources, or to use the `--keep-generated` option.**

## Settings

This module add the capability to enable or disable this adapter connector.  
The setting is available at the Orange Sms config section.

Now, your Orange Sms connector is available in the field list of Sms Notification adapters.  
Select `Orange Sms` and apply the changes. Now, your notification are sent through Orange Sms.   

## Support

- If you have any issue with this code, feel free to [open an issue](https://github.com/blackbird-agency/magento-2-orange-sms-notification/issues/new).  
- If you want to contribute to this project, feel free to [create a pull request](https://github.com/blackbird-agency/magento-2-orange-sms-notification/compare).

## Contact

For further information, contact us:

- by email: hello@bird.eu
- or by form: [https://black.bird.eu/en/contacts/](https://black.bird.eu/contacts/)

## Authors

- **Thomas Klein** - *Maintainer* - [It's me!](https://github.com/thomas-blackbird)
- **Blackbird Team** - *Contributor* - [They're awesome!](https://github.com/blackbird-agency)

## Licence

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

***That's all folks!***
