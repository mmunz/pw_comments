<?php
namespace T3\PwComments\Utility;

/*  | This extension is made for TYPO3 CMS and is licensed
 *  | under GNU General Public License.
 *  |
 *  | (c) 2011-2018 Armin Vieweg <armin@v.ieweg.de>
 *  |     2015 Dennis Roemmich <dennis@roemmich.eu>
 *  |     2016-2017 Christian Wolfram <c.wolfram@chriwo.de>
 */

/**
 * Cookie Utility
 *
 * @package T3\PwComments
 */
class Cookie
{
    /** Cookie Prefix */
    const COOKIE_PREFIX = 'tx_pwcomments_';
    /** Lifetime of cookie in days */
    const COOKIE_LIFETIME_DAYS = 365;

    /**
     * Get cookie value
     *
     * @param string $key
     * @return string|null
     */
    public static function get($key)
    {
        if (isset($_COOKIE[self::COOKIE_PREFIX . $key])) {
            return $_COOKIE[self::COOKIE_PREFIX . $key];
        }
        return null;
    }

    /**
     * Set cookie value
     *
     * @param string $key
     * @param string $value
     * @return void
     */
    public static function set($key, $value)
    {
        $cookieExpireDate = time() + self::COOKIE_LIFETIME_DAYS * 24 * 60 * 60;
        setcookie(
            self::COOKIE_PREFIX . $key,
            $value,
            $cookieExpireDate,
            '/',
            self::getCookieDomain(),
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['cookieSecure'] > 0,
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['cookieHttpOnly'] == 1
        );
    }

    /**
     * Gets the domain to be used on setting cookies. The information is
     * taken from the value in $GLOBALS['TYPO3_CONF_VARS']['SYS']['cookieDomain']
     *
     * @return string The domain to be used on setting cookies
     */
    protected static function getCookieDomain()
    {
        $result = '';
        $cookieDomain = $GLOBALS['TYPO3_CONF_VARS']['SYS']['cookieDomain'];
        if (!empty($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['cookieDomain'])) {
            $cookieDomain = $GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['cookieDomain'];
        }
        if ($cookieDomain) {
            if ($cookieDomain[0] == '/') {
                $match = [];
                $matchCnt = @preg_match(
                    $cookieDomain,
                    \TYPO3\CMS\Core\Utility\GeneralUtility::getIndpEnv('TYPO3_HOST_ONLY'),
                    $match
                );
                if ($matchCnt !== false) {
                    $result = $match[0];
                }
            } else {
                $result = $cookieDomain;
            }
        }
        return $result;
    }
}
