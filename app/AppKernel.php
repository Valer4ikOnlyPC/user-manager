<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

/**
 * Application kernel
 */
class AppKernel extends Kernel
{
    /**
     * @var string
     */
    protected $webDir;

    /**
     * @var string
     */
    protected $filesDir;

    /**
     * @param string $environment
     * @param bool $debug
     */
    public function __construct($environment, $debug)
    {
        parent::__construct($environment, $debug);

        $rootDir = $this->getRootDir();
        $this->webDir = realpath($rootDir . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'web');
        $this->filesDir = realpath($rootDir . DIRECTORY_SEPARATOR . 'files');
    }

    /**
     * @return array
     */
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Liip\ImagineBundle\LiipImagineBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'), true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
        }

        return $bundles;
    }

    /**
     * @param LoaderInterface $loader
     *
     * @return void
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir() . '/config/config_' . $this->getEnvironment() . '.yml');
    }

    /**
     * @return string
     */
    public function getWebDir()
    {
        return $this->webDir;
    }

    /**
     * @return string
     */
    public function getFilesDir()
    {
        return $this->filesDir;
    }

    /**
     * @return string
     */
    public function getLogDir()
    {
        return realpath($this->getRootDir().'/../files/logs');
    }
}