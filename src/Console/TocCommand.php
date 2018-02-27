<?php
declare(strict_types=1);

namespace PhpSnippetPost\Console;

use PhpSnippetPost\File\EntryFile;
use PhpSnippetPost\File\EntryFiles;
use PhpSnippetPost\File\Post;
use PhpSnippetPost\File\Posts;
use Symfony\Component\Console\Input\InputArgument;

final class TocCommand extends Command
{
    /**
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function configure()
    {
        $this->setName('toc')
            ->setDescription('Generate table of content')
            ->addArgument(
                'paths',
                InputArgument::IS_ARRAY,
                'Paths of file or directory for post'
            );
    }

    /**
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function fire()
    {
        $entryNames = ['index.php'];
        $paths = $this->input->getArgument('paths');
        $paths = EntryFiles::fromPaths($paths, $entryNames)
            ->map(function (EntryFile $file) { return $file->path(); })
            ->filter()
            ->all();
        $posts = Posts::fromFiles($paths);

        $content = $posts->reduce(function ($content, Post $post) {
            $content = implode(PHP_EOL, [
                $content,
                "### {$post->getTitle()}",
                "{$post->getDate()} [source](./{$post->getPath()})",
            ]);
            if (!empty($post->getBody())) {
                $content .= PHP_EOL . trim((string)$post->getBody());
            }
            return $content . PHP_EOL;
        });
        echo '# コードリスト' . PHP_EOL . PHP_EOL;
        echo trim($content);
    }
}