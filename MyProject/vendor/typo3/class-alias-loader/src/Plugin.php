<?php
namespace TYPO3\ClassAliasLoader;

/*
 * This file is part of the class alias loader package.
 *
 * (c) Helmut Hummel <info@helhum.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;

/**
 * Class Plugin
 */
class Plugin implements PluginInterface, EventSubscriberInterface
{
    /**
     * @var Composer
     */
    protected $composer;

    /**
     * @var IOInterface
     */
    protected $io;

    /**
     * @var ClassAliasMapGenerator
     */
    private $aliasMapGenerator;

    /**
     * Apply plugin modifications to composer
     *
     * @param Composer $composer
     * @param IOInterface $io
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        $this->composer = $composer;
        $this->io = $io;
        $this->aliasMapGenerator = new ClassAliasMapGenerator(
            $this->composer,
            $this->io
        );
    }

    public function deactivate(Composer $composer, IOInterface $io)
    {
        // Nothing to do
    }

    public function uninstall(Composer $composer, IOInterface $io)
    {
        // Nothing to do
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     * * The method name to call (priority defaults to 0)
     * * An array composed of the method name to call and the priority
     * * An array of arrays composed of the method names to call and respective
     *   priorities, or 0 if unset
     *
     * For instance:
     *
     * * array('eventName' => 'methodName')
     * * array('eventName' => array('methodName', $priority))
     * * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return array(
            'pre-autoload-dump' => array('onPreAutoloadDump'),
            'post-autoload-dump' => array('onPostAutoloadDump'),
        );
    }

    /**
     * @param Event $event
     * @throws \Exception
     * @return bool
     */
    public function onPreAutoloadDump(Event $event)
    {
        return $this->aliasMapGenerator->generateAliasMapFiles();
    }

    /**
     * @param Event $event
     * @return bool
     */
    public function onPostAutoloadDump(Event $event)
    {
        $flags = $event->getFlags();
        $config = $event->getComposer()->getConfig();
        $optimizeAutoloadFiles = !empty($flags['optimize']) || $config->get('optimize-autoloader') || $config->get('classmap-authoritative');

        return $this->aliasMapGenerator->modifyComposerGeneratedFiles($optimizeAutoloadFiles);
    }
}
