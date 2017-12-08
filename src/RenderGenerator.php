<?php

namespace Codeception\Command;

use PHPUnitGenerator\Model\ModelInterface\ClassModelInterface;
use PHPUnitGenerator\Renderer\RendererInterface\TestRendererInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class RenderGenerator implements TestRendererInterface
{

    /**
     * @var string DEFAULT_TEMPLATE_FOLDER The default template folder
     */
    const DEFAULT_TEMPLATE_FOLDER = '/Template/';

    /**
     * @var \Twig\Environment $twig The template renderer
     */
    private $twig;

    /**
     * TwigTestRenderer constructor
     */
    public function __construct()
    {
        // Create the FileSystemLoader
        $loader = $this->getFilesystemLoader(__DIR__ . static::DEFAULT_TEMPLATE_FOLDER);

        // Create twig configuration
        $twigConfig['cache'] = false;
        $twigConfig['debug'] = false;

        // Create Twig renderer environment
        $this->twig = new Environment($loader, [
            'cache' => false,
            'debug' => false
        ]);

        // Add method lcfirst
        $this->twig->addFilter(new \Twig_SimpleFilter('lcfirst', 'lcfirst'));
    }

    /**
     * {@inheritdoc}
     */
    public function render(ClassModelInterface $classModel): string
    {
        return $this->twig->render('class.twig', [
            'class' => $classModel
        ]);
    }

    /**
     * Construct a new instance of FilesystemLoader
     *
     * @param array|string $path
     *
     * @return FilesystemLoader
     */
    protected function getFilesystemLoader($path): FilesystemLoader
    {
        return new FilesystemLoader($path);
    }
}
