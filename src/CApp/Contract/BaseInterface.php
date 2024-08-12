<?php
namespace Cresenity\Laravel\CApp\Contract;

/**
 * @see CApp_Base
 * @see CApp_Trait_BaseTrait
 */
interface BaseInterface
{
    /**
     * @return int
     */
    public static function appId();

    /**
     * @return string
     */
    public static function appCode();

    /**
     * @return null|int
     */
    public static function orgId();

    /**
     * @param int $orgId optional, default using return values of orgId()
     *
     * @return string Code of org
     */
    public static function orgCode($orgId = null);

    /**
     * User ID from session.
     *
     * @return int
     */
    public static function userId();

    /**
     * Get username.
     *
     * @return string
     */
    public static function username();
}
