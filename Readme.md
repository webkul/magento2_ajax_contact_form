# Installation

Magento2 AjaxContactForm module's installation is very easy, please follow the steps for installation-

1. Unzip the respective extension zip and create Webkul(vendor) and AjaxContactForm(module) name folder inside your magento/app/code/ directory and then move all module's files into magento root directory/app/code/Webkul/ProductCarousel/ folder.

or

# Install with Composer as you go

Specify the version of the module you need, and go.
<pre>
    <code>composer require webkul/magento2_ajax_contact_form</code>
</pre>

# Run Following Command via terminal
-----------------------------------
<pre>
    <code>
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy
    </code>
</pre>

2. Flush the cache and reindex all.

now module is properly installed

# User Guide

For Magento2 AjaxContactForm module's working process follow user guide - https://webkul.com/blog/magento-2-ajax-contact-form/
