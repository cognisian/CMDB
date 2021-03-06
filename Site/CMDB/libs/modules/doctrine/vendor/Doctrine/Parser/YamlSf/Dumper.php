<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/* removed since it now use the doctrine autoload feature
 * require_once(dirname(__FILE__).'/Yml_Inline.class.php');
 */

/**
 * YamlSfDumper class.
 *
 * @package    symfony
 * @subpackage util
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: Dumper.php 1075 2009-03-31 21:16:19Z schalme $
 */
class Doctrine_Parser_YamlSf_Dumper
{
  /**
   * Dumps a PHP value to YAML.
   *
   * @param  mixed   The PHP value
   * @param  integer The level where you switch to inline YAML
   * @param  integer The level o indentation indentation (used internally)
   *
   * @return string  The YAML representation of the PHP value
   */
  public function dump($input, $inline = 0, $indent = 0)
  {
    $output = '';
    $prefix = $indent ? str_repeat(' ', $indent) : '';

    if ($inline <= 0 || !is_array($input) || empty($input))
    {
      $output .= $prefix.Doctrine_Parser_YamlSf_Inline::dump($input);
    }
    else
    {
      $isAHash = count(array_diff_key($input, array_fill(0, count($input), true)));

      foreach ($input as $key => $value)
      {
        $willBeInlined = $inline - 1 <= 0 || !is_array($value) || empty($value);

        $output .= sprintf('%s%s%s%s',
          $prefix,
          $isAHash ? Doctrine_Parser_YamlSf_Inline::dump($key).':' : '-',
          $willBeInlined ? ' ' : "\n",
          $this->dump($value, $inline - 1, $willBeInlined ? 0 : $indent + 2)
        ).($willBeInlined ? "\n" : '');
      }
    }

    return $output;
  }
}