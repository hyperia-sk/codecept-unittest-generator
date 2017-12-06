<?php

/**
 * An example for a custom command to add to the framework.
 *
 * @author    Tobias Matthaiou <tm@solutionDrive.de>
 * @date      27.01.16
 */

namespace Codeception\Command;

use \Symfony\Component\Console\Command\Command;
use \Codeception\CustomCommandInterface;
use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\OutputInterface;
use \PHPUnitGenerator\Generator\TestGenerator;
use \PHPUnitGenerator\Config\Config;
use \PHPUnitGenerator\Exception\ExceptionInterface\ExceptionInterface;

class UnitGenerator extends Command implements CustomCommandInterface
{
    use \Codeception\Command\Shared\FileSystem;
    use \Codeception\Command\Shared\Config;
    use \Codeception\Command\Shared\Style;

    /**
     * returns the name of the command
     *
     * @return string
     */
    public static function getCommandName()
    {
        return "generate:unit";
    }

    /**
     * Configures the current command.
     */
    protected function configure()
    {
        parent::configure();
    }

    /**
     * Returns the description for the command.
     *
     * @return string The description for the command
     */
    public function getDescription()
    {
        return "Generates unit tests from annotations";
    }

    /**
     * Executes the current command.
     *
     * This method is not abstract because you can use this class
     * as a concrete class. In this case, instead of defining the
     * execute() method, you set the code to execute by passing
     * a Closure to the setCode() method.
     *
     * @param \Symfony\Component\Console\Input\InputInterface   $input  An InputInterface instance
     * @param \Symfony\Component\Console\Output\OutputInterface $output An OutputInterface instance
     *
     * @return null|int null or 0 if everything went fine, or an error code
     *
     * @throws LogicException When this abstract method is not implemented
     *
     * @see setCode()
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->addStyles($output);
        $config = $this->getGlobalConfig()['unitgenerator'];
        $testGenerator = new TestGenerator(new Config($config['config']));
        $testGenerator->setTestRenderer(new RenderGenerator());

        $count = 0;
        $output->writeln("\n<comment>Generates unit tests from annotations:</comment>\n");
        try {
            foreach ($config['dirs'] as $dir) {
                $source = key($dir);
                $target = current($dir);
                $tests = $testGenerator->writeDir($source, $target);
                $count += $tests;
                
                $output->writeln(" Process <debug>" . $source. "</debug>");
                $output->writeln(" $tests tests was created in $target\n");
            }
            
            $output->writeln("<info>Done, $count tests was created.</info>");
        } catch (ExceptionInterface $e) {
            $output->writeln("<error>{$e->getMessage()}</error>");
        }
    }
}
