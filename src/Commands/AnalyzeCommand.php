<?php

/**
 * File for AnalyzeCommand class.
 */

namespace DrupalMaintainerShield\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use DrupalMaintainerShield\Analyzer;

/**
 * Class AnalyzeCommand
 *
 * CLI command to analyze patches.
 */
class AnalyzeCommand extends Command
{
    protected static $defaultName = 'analyze';

    /**
     * Configures the command.
     */
    protected function configure(): void
    {
        $this
            ->setName('analyze')
            ->setDescription('Analyzes a patch or issue description for security signal vs noise.')
            ->addArgument('file', InputArgument::REQUIRED, 'Path to the patch file to analyze.');
    }

    /**
     * Executes the command.
     *
     * @param InputInterface  $input  The input.
     * @param OutputInterface $output The output.
     *
     * @return int The exit code.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $filePath = $input->getArgument('file');

        if (!file_exists($filePath)) {
            $io->error("File not found: $filePath");
            return Command::FAILURE;
        }

        $content = file_get_contents($filePath);
        $analyzer = new Analyzer();
        $result = $analyzer->analyze($content);

        $io->title("Drupal Maintainer Shield: Analysis Report");

        $io->section("General Results");
        $io->text("Recommendation: " . $result['recommendation']);
        $io->text("Confidence Score: " . $result['score'] . "/100");

        $io->section("Findings");
        if (empty($result['findings'])) {
            $io->text("No significant patterns detected.");
        } else {
            $io->listing($result['findings']);
        }

        $io->section("Metrics");
        $io->text("Quality Signals: " . $result['quality_signals']);
        $io->text("Noise Signals: " . $result['noise_signals']);

        if ($result['score'] < 30) {
            $io->warning("Contribution matches AI noise patterns. Proceed with caution.");
        } elseif ($result['score'] > 70) {
            $io->success("High-signal security contribution. Priority review recommended.");
        }

        return Command::SUCCESS;
    }
}
