<?php

namespace Ssch\TYPO3Rector\Tests\TypoScript\Visitors;

use Helmich\TypoScriptParser\Parser\AST\ConditionalStatement;
use Iterator;
use PHPUnit\Framework\TestCase;
use Ssch\TYPO3Rector\TypoScript\Conditions\ApplicationContextConditionMatcher;
use Ssch\TYPO3Rector\TypoScript\Conditions\BrowserConditionMatcher;
use Ssch\TYPO3Rector\TypoScript\Conditions\CompatVersionConditionMatcher;
use Ssch\TYPO3Rector\TypoScript\Conditions\GlobalStringConditionMatcher;
use Ssch\TYPO3Rector\TypoScript\Conditions\GlobalVarConditionMatcher;
use Ssch\TYPO3Rector\TypoScript\Conditions\IPConditionMatcher;
use Ssch\TYPO3Rector\TypoScript\Conditions\LanguageConditionMatcher;
use Ssch\TYPO3Rector\TypoScript\Conditions\LoginUserConditionMatcher;
use Ssch\TYPO3Rector\TypoScript\Conditions\PageConditionMatcher;
use Ssch\TYPO3Rector\TypoScript\Conditions\PIDinRootlineConditionMatcher;
use Ssch\TYPO3Rector\TypoScript\Conditions\PIDupinRootlineConditionMatcher;
use Ssch\TYPO3Rector\TypoScript\Conditions\TimeConditionMatcher;
use Ssch\TYPO3Rector\TypoScript\Conditions\TreeLevelConditionMatcher;
use Ssch\TYPO3Rector\TypoScript\Conditions\UsergroupConditionMatcherMatcher;
use Ssch\TYPO3Rector\TypoScript\Conditions\VersionConditionMatcher;
use Ssch\TYPO3Rector\TypoScript\Visitors\OldConditionToExpressionLanguageVisitor;

final class OldConditionToExpressionLanguageVisitorTest extends TestCase
{
    /**
     * @var OldConditionToExpressionLanguageVisitor
     */
    private $subject;

    protected function setUp(): void
    {
        $conditionMatchers = [
            new ApplicationContextConditionMatcher(),
            new BrowserConditionMatcher(),
            new CompatVersionConditionMatcher(),
            new GlobalStringConditionMatcher(),
            new GlobalVarConditionMatcher(),
            new IPConditionMatcher(),
            new LanguageConditionMatcher(),
            new LoginUserConditionMatcher(),
            new PageConditionMatcher(),
            new PIDinRootlineConditionMatcher(),
            new PIDupinRootlineConditionMatcher(),
            new TimeConditionMatcher(),
            new TreeLevelConditionMatcher(),
            new UsergroupConditionMatcherMatcher(),
            new VersionConditionMatcher(),
        ];

        $this->subject = new OldConditionToExpressionLanguageVisitor($conditionMatchers);
    }

    public function statements(): Iterator
    {
        yield 'TSFE multiple ids' => [
            'oldCondition' => '[globalVar = TSFE:id=17, TSFE:id=24]',
            'newCondition' => '[getTSFE().id in [17,24]]',
        ];

        yield 'Type with compatVersion' => [
            'oldCondition' => '[globalVar = TSFE:type = 1451160842] && [compatVersion = 7.6.0] || [globalVar = TSFE:type = 1449874941] && [compatVersion = 7.6.0]',
            'newCondition' => '[getTSFE().type == 1451160842 && compatVersion("7.6.0") || getTSFE().type == 1449874941 && compatVersion("7.6.0")]',
        ];

        yield 'multiple conditions with default OR operator' => [
            'oldCondition' => '[applicationContext=Development/Debugging][applicationContext=Development/ClientA]',
            'newCondition' => '[applicationContext == Development/Debugging || applicationContext == Development/ClientA]',
        ];

        yield 'multiple conditions with AND operator' => [
            'oldCondition' => '[applicationContext=Development/Debugging] && [applicationContext=Development/ClientA]',
            'newCondition' => '[applicationContext == Development/Debugging && applicationContext == Development/ClientA]',
        ];

        yield 'applicationContext equals' => [
            'oldCondition' => '[applicationContext=Development/Debugging, Development/ClientA]',
            'newCondition' => '[applicationContext == Development/Debugging || applicationContext == Development/ClientA]',
        ];

        yield 'applicationContext matches' => [
            'oldCondition' => '[applicationContext = /^Development\/Preview\/d+$/]',
            'newCondition' => '[applicationContext matches /^Development\/Preview\/d+$/]',
        ];

        yield 'Keep new applicationContext condition' => [
            'oldCondition' => '[applicationContext == Development/Debugging || applicationContext == Development/ClientA]',
            'newCondition' => '[applicationContext == Development/Debugging || applicationContext == Development/ClientA]',
        ];

        yield 'PIDinRootline' => [
            'oldCondition' => '[PIDinRootline = 34,36]',
            'newCondition' => '[34 in tree.rootLineIds || 36 in tree.rootLineIds]',
        ];

        yield 'PIDinRootline and applicationContext' => [
            'oldCondition' => '[PIDinRootline = 34,36] && [applicationContext=Development/ClientA]',
            'newCondition' => '[34 in tree.rootLineIds || 36 in tree.rootLineIds && applicationContext == Development/ClientA]',
        ];

        yield 'PIDupinRootline' => [
            'oldCondition' => '[PIDupinRootline = 17, 24]',
            'newCondition' => '[17 in tree.rootLineParentIds || 24 in tree.rootLineParentIds]',
        ];

        yield 'loginUser any' => [
            'oldCondition' => '[loginUser = *]',
            'newCondition' => '[loginUser("*")]',
        ];

        yield 'Keep new condition loginUser' => [
            'oldCondition' => '[loginUser("*")]',
            'newCondition' => '[loginUser("*")]',
        ];

        yield 'loginUser not logged in' => [
            'oldCondition' => '[loginUser = ]',
            'newCondition' => '[loginUser("*") == false]',
        ];

        yield 'loginUser with ids' => [
            'oldCondition' => '[loginUser = 1,2]',
            'newCondition' => '[loginUser("1,2")]',
        ];

        yield 'page' => [
            'oldCondition' => '[page|field = value]',
            'newCondition' => '[page["field"] == "value"]',
        ];

        yield 'Keep new page condition' => [
            'oldCondition' => '[page["field"] == "value"]',
            'newCondition' => '[page["field"] == "value"]',
        ];

        yield 'TSFE id' => [
            'oldCondition' => '[globalVar = TSFE:id >= 10]',
            'newCondition' => '[getTSFE().id >= 10]',
        ];

        yield 'TSFE multiple page ids' => [
            'oldCondition' => '[globalVar = TSFE:page|pid=17, TSFE:page|pid=24]',
            'newCondition' => '[page["pid"] in [17,24]]',
        ];

        yield 'Site language' => [
            'oldCondition' => '[globalVar = GP:L = 1]',
            'newCondition' => '[siteLanguage("languageId") == "1"]',
        ];

        yield 'Version condition is removed' => [
            'oldCondition' => '[version => 8]',
            'newCondition' => '',
        ];

        yield 'Browser condition is removed' => [
            'oldCondition' => '[browser => 8]',
            'newCondition' => '',
        ];

        yield 'GP parameter' => [
            'oldCondition' => '[globalVar = GP:tx_myext_myplugin|bla > 0]',
            'newCondition' => "[traverse(request.getQueryParams(), 'tx_myext_myplugin/bla') > 0 || traverse(request.getParsedBody(), 'tx_myext_myplugin/bla') > 0]",
        ];

        yield 'ENV:HTTP_HOST' => [
            'oldCondition' => '[globalString = ENV:HTTP_HOST = www.domain.de, ENV:HTTP_HOST = www.domain.com]',
            'newCondition' => '[getenv("HTTP_HOST") == "www.domain.de" || getenv("HTTP_HOST") == "www.domain.com"]',
        ];

        yield 'IENV:HTTP_HOST with wildcard' => [
            'oldCondition' => '[globalString = IENV:HTTP_HOST = *.devbox.local]',
            'newCondition' => '[like(request.getNormalizedParams().getHttpHost(), "*.devbox.local")]',
        ];

        yield 'IENV:HTTP_HOST' => [
            'oldCondition' => '[globalString = IENV:HTTP_HOST = www.example.org]',
            'newCondition' => '[request.getNormalizedParams().getHttpHost() == "www.example.org"]',
        ];

        yield 'beUserLogin' => [
            'oldCondition' => '[globalVar = TSFE:beUserLogin > 0]',
            'newCondition' => '[getTSFE().beUserLogin > 0]',
        ];

        yield 'treeLevel' => [
            'oldCondition' => '[treeLevel = 0,2]',
            'newCondition' => '[tree.level in [0,2]]',
        ];

        yield 'language' => [
            'oldCondition' => '[language = ###LANG_ISO_LOWER###]',
            'newCondition' => '[siteLanguage("twoLetterIsoCode") == "###LANG_ISO_LOWER###"]',
        ];

        yield 'TSFE id with constant' => [
            'oldCondition' => '[globalVar = TSFE:id = {$plugin.tx_ppwsiprograms.settings.schooldetailPage}]',
            'newCondition' => '[getTSFE().id == {$plugin.tx_ppwsiprograms.settings.schooldetailPage}]',
        ];

        yield 'TSFE type condition' => [
            'oldCondition' => '[globalVar = TSFE:type = 1451160842]',
            'newCondition' => '[getTSFE().type == 1451160842]',
        ];

        yield 'TSFE type conditions multiple' => [
            'oldCondition' => '[globalVar = TSFE:type >= 9900] && [globalVar = TSFE:type <= 9999]',
            'newCondition' => '[getTSFE().type >= 9900 && getTSFE().type <= 9999]',
        ];

        yield 'Http Host with beUserLogin' => [
            'oldCondition' => '[globalString = ENV:HTTP_HOST = www.stadtbetrieb-bornheim.de, ENV:HTTP_HOST = www.hallenfreizeitbad.de] && [globalVar = TSFE : beUserLogin = 0]',
            'newCondition' => '[getenv("HTTP_HOST") == "www.stadtbetrieb-bornheim.de" || getenv("HTTP_HOST") == "www.hallenfreizeitbad.de" && getTSFE().beUserLogin == 0]',
        ];

        yield 'GP print is greater than zero' => [
            'oldCondition' => '[globalVar = GP:print > 0]',
            'newCondition' => "[request.getQueryParams()['print'] > 0]",
        ];

        yield 'Dayofweek greater than' => [
            'oldCondition' => '[dayofweek = > 5]',
            'newCondition' => '[date("w") > 5]',
        ];

        yield 'Dayofmonth equals' => [
            'oldCondition' => '[dayofmonth = 5]',
            'newCondition' => '[date("j") == 5]',
        ];

        yield 'Hour' => [
            'oldCondition' => '[hour = > 5, < 7]',
            'newCondition' => '[date("G") > 5 || date("G") < 7]',
        ];

        yield 'Dayofmonth and hour' => [
            'oldCondition' => '[hour = > 5, < 7] && [dayofmonth = < 5, > 6]',
            'newCondition' => '[date("G") > 5 || date("G") < 7 && date("j") < 5 || date("j") > 6]',
        ];

        yield 'IP Address' => [
            'oldCondition' => '[IP = *.*.*.123][IP = devIP]',
            'newCondition' => '[ip("*.*.*.123") || ip("devIP")]',
        ];

        yield 'LIT equals' => [
            'oldCondition' => '[globalVar = LIT:1 = {$meineTypoScriptKonstante}]',
            'newCondition' => '["{$meineTypoScriptKonstante}" == "1"]',
        ];

        yield 'LIT greater than' => [
            'oldCondition' => '[globalVar = LIT:{$meineTypoScriptKonstante} > 0]',
            'newCondition' => '["0" > "{$meineTypoScriptKonstante}"]',
        ];

        yield 'Multiple usergroups' => [
            'oldCondition' => '[usergroup = 1,2]',
            'newCondition' => '[usergroup("1,2")]',
        ];

        yield 'Keep new usergroups condition' => [
            'oldCondition' => '[usergroup("1,2")]',
            'newCondition' => '[usergroup("1,2")]',
        ];
    }

    /**
     * @dataProvider statements
     */
    public function testEnterNode(string $oldCondition, string $newCondition): void
    {
        $conditionalStatement = new ConditionalStatement($oldCondition, [], [], 1);
        $this->subject->enterNode($conditionalStatement);
        $this->assertSame($newCondition, $conditionalStatement->condition);
    }
}
