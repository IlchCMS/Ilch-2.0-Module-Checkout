<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Checkout\Config;

class Config extends \Ilch\Config\Install
{
    public $config = [
        'key' => 'checkout',
        'version' => '1.2.0',
        'icon_small' => 'fa-credit-card',
        'author' => 'Stantin, Thomas',
        'link' => 'http://ilch.de',
        'languages' => [
            'de_DE' => [
                'name' => 'Kasse INTL',
                'description' => 'Hier kann die Clan-Kasse gepflegt werden.',
            ],
            'en_EN' => [
                'name' => 'Checkout INTL',
                'description' => 'Here you can manage your clan cash.',
            ],
        ],
        'ilchCore' => '2.1.15',
        'phpVersion' => '5.6',
        'phpExtensions' => [
            'intl'
        ]
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());

        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->set('checkout_contact', '<p>Kontoinhaber: Max Mustermann</p><p>Bankname: Muster Sparkasse</p><p>Kontonummer: 123</p><p>Bankleitzahl: 123</p><p>BIC: 123</p><p>IBAN: 123</p><p>Verwendungszweck: Spende f&uuml;r ilch.de ;-)</p>');
        $databaseConfig->set('checkout_currency', '1');
    }

    public function uninstall()
    {
        $this->db()->queryMulti('DROP TABLE `[prefix]_checkout`');
        $this->db()->queryMulti('DROP TABLE `[prefix]_checkout_currencies`');
        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'checkout_contact'");
        $this->db()->queryMulti("DELETE FROM `[prefix]_config` WHERE `key` = 'checkout_currency'");
    }

    public function getInstallSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_checkout` (
                  `id` INT(14) NOT NULL AUTO_INCREMENT,
                  `date_created` DATETIME NOT NULL,
                  `name` VARCHAR(255) NOT NULL,
                  `usage` VARCHAR(255) NOT NULL,
                  `amount` FLOAT NOT NULL DEFAULT 0,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_checkout_currencies` (
                  `id` INT(14) NOT NULL AUTO_INCREMENT,
                  `name` VARCHAR(255) NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

                INSERT INTO `[prefix]_checkout_currencies` (`id`, `name`) VALUES (1, "EUR");
                INSERT INTO `[prefix]_checkout_currencies` (`id`, `name`) VALUES (2, "USD");
                INSERT INTO `[prefix]_checkout_currencies` (`id`, `name`) VALUES (3, "GBP");
                INSERT INTO `[prefix]_checkout_currencies` (`id`, `name`) VALUES (4, "AUD");
                INSERT INTO `[prefix]_checkout_currencies` (`id`, `name`) VALUES (5, "NZD");
                INSERT INTO `[prefix]_checkout_currencies` (`id`, `name`) VALUES (6, "CHF");';
    }

    public function getUpdate($installedVersion)
    {
        switch ($installedVersion) {
            case "1.0.0":
            case "1.1.0":
                // Convert tables to new character set and collate
                $this->db()->query('ALTER TABLE `[prefix]_checkoutbasic` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
                $this->db()->query('ALTER TABLE `[prefix]_checkoutbasic_currencies` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;');
        }
    }
}
