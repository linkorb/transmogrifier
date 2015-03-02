<?php
namespace LinkORB\Transmogrifier\Parser;

use PHPUnit_Extensions_Database_DataSet_IYamlParser;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Parser;

class YamlParser implements PHPUnit_Extensions_Database_DataSet_IYamlParser
{
    /**
     * @param string $yamlFile
     * @return array parsed YAML
     */
    public function parseYaml($yamlFile)
    {
        $yaml = new Parser();
        try {
            $value = $yaml->parse(file_get_contents($yamlFile));
        } catch (ParseException $e) {
            throw $e;
        }
        return $value;
    }
}
