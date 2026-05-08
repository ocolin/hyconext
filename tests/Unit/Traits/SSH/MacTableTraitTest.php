<?php

declare( strict_types = 1 );

namespace Ocolin\Hyconext\Tests\Unit\Traits\SSH;

use Ocolin\Hyconext\DTO\SSH\MacEntry;
use Ocolin\Hyconext\DTO\SSH\MacStats;
use Ocolin\Hyconext\DTO\SSH\MacTable;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use RuntimeException;

#[CoversClass( MacTable::class )]
#[CoversClass( MacStats::class )]
#[CoversClass( MacEntry::class )]
#[Group( 'unit' )]
class MacTableTraitTest extends TestCase
{

    /**
     * Raw output from a real Hyconext switch session.
     * Captured from: ssh admin@10.35.3.2 -> show mac-address
     * Note: \r\n line endings are normalized to \n by getMacTable() before parsing.
     */
    private string $rawOutput;

    private object $trait;


    /* SET UP
    ----------------------------------------------------------------------------- */

    protected function setUp() : void
    {
        // Build fixture that mirrors real device output after \r\n normalization
        // Part 0: command echo (discarded)
        // Part 1: stats block
        // Part 2: entries table
        $this->rawOutput = implode( "\n\n", [
            "show mac-address",
            "  Total           :124\n" .
            "  Static          :1\n" .
            "  Dynamic         :123\n" .
            "  Blackhole       :0\n" .
            "  Sticky          :0\n" .
            "  Security        :0\n" .
            "  Snooping        :0\n" .
            "  Valid           :124",
            "  MAC Address             Vlan/Vsi/BD         Interface               Oper-Type         Type        \n" .
            "  00:0b:78:66:db:d0       1/--/--             10ge1/1/1               forward           dynamic     \n" .
            "  08:55:31:2d:3f:00       10/--/--            10ge1/1/1               forward           dynamic     \n" .
            "  e4:8d:8c:7e:77:45       205/--/--           2.5ge1/0/1              forward           dynamic     \n" .
            "  d4:01:c3:02:88:6f       1/--/--             10ge1/1/1               forward           static      \n" .
            "877Cedar.POESW#",
        ]);

        // Create an anonymous class that uses the trait so we can call private methods via reflection
        $this->trait = new class {
            use \Ocolin\Hyconext\Traits\SSH\MacTableTrait;

            // Expose private methods for testing via public wrappers
            public function callParseStats( string $input ) : MacStats
            {
                return self::parseStats( $input );
            }

            public function callParseEntries( string $input ) : array
            {
                return self::parseEntries( $input );
            }

            public function callRunCommand( string $command ) : string
            {
                return '';
            }
        };
    }



    /* PARSE STATS TESTS
    ----------------------------------------------------------------------------- */

    #[Test]
    public function parseStatsReturnsCorrectTotals() : void
    {
        $stats = $this->trait->callParseStats(
            "  Total           :124\n" .
            "  Static          :1\n" .
            "  Dynamic         :123\n" .
            "  Blackhole       :0\n" .
            "  Sticky          :0\n" .
            "  Security        :0\n" .
            "  Snooping        :0\n" .
            "  Valid           :124"
        );

        $this->assertInstanceOf( MacStats::class, $stats );
        $this->assertSame( 124, $stats->total );
        $this->assertSame( 1,   $stats->static );
        $this->assertSame( 123, $stats->dynamic );
        $this->assertSame( 0,   $stats->blackhole );
        $this->assertSame( 0,   $stats->sticky );
        $this->assertSame( 0,   $stats->security );
        $this->assertSame( 0,   $stats->snooping );
        $this->assertSame( 124, $stats->valid );
    }

    #[Test]
    public function parseStatsDefaultsToZeroForMissingFields() : void
    {
        $stats = $this->trait->callParseStats(
            "  Total           :5\n" .
            "  Dynamic         :5"
        );

        $this->assertSame( 5, $stats->total );
        $this->assertSame( 5, $stats->dynamic );
        $this->assertSame( 0, $stats->static );
        $this->assertSame( 0, $stats->blackhole );
    }

    #[Test]
    public function parseStatsHandlesExtraWhitespace() : void
    {
        $stats = $this->trait->callParseStats(
            "  Total           :10\n" .
            "  Static          :2\n" .
            "  Dynamic         :8\n" .
            "  Blackhole       :0\n" .
            "  Sticky          :0\n" .
            "  Security        :0\n" .
            "  Snooping        :0\n" .
            "  Valid           :10"
        );

        $this->assertSame( 10, $stats->total );
        $this->assertSame( 2,  $stats->static );
        $this->assertSame( 8,  $stats->dynamic );
    }

    #[Test]
    public function parseStatsIgnoresLinesWithoutColons() : void
    {
        $stats = $this->trait->callParseStats(
            "  Some random line\n" .
            "  Total           :5\n" .
            "  Dynamic         :5\n" .
            "  Another random line"
        );

        $this->assertSame( 5, $stats->total );
        $this->assertSame( 5, $stats->dynamic );
    }



    /* PARSE ENTRIES TESTS
    ----------------------------------------------------------------------------- */

    #[Test]
    public function parseEntriesReturnsCorrectCount() : void
    {
        $entries = $this->trait->callParseEntries(
            "  MAC Address             Vlan/Vsi/BD         Interface               Oper-Type         Type        \n" .
            "  00:0b:78:66:db:d0       1/--/--             10ge1/1/1               forward           dynamic     \n" .
            "  08:55:31:2d:3f:00       10/--/--            10ge1/1/1               forward           dynamic     \n" .
            "  e4:8d:8c:7e:77:45       205/--/--           2.5ge1/0/1              forward           dynamic     \n" .
            "  d4:01:c3:02:88:6f       1/--/--             10ge1/1/1               forward           static      \n" .
            "877Cedar.POESW#"
        );

        $this->assertCount( 4, $entries );
    }

    #[Test]
    public function parseEntriesReturnsCorrectMacAddress() : void
    {
        $entries = $this->trait->callParseEntries(
            "  MAC Address             Vlan/Vsi/BD         Interface               Oper-Type         Type        \n" .
            "  00:0b:78:66:db:d0       1/--/--             10ge1/1/1               forward           dynamic     \n" .
            "877Cedar.POESW#"
        );

        $this->assertSame( '00:0b:78:66:db:d0', $entries[0]->mac );
    }

    #[Test]
    public function parseEntriesReturnsCorrectInterface() : void
    {
        $entries = $this->trait->callParseEntries(
            "  MAC Address             Vlan/Vsi/BD         Interface               Oper-Type         Type        \n" .
            "  00:0b:78:66:db:d0       1/--/--             10ge1/1/1               forward           dynamic     \n" .
            "877Cedar.POESW#"
        );

        $this->assertSame( '10ge1/1/1', $entries[0]->interface );
    }

    #[Test]
    public function parseEntriesReturnsCorrectVlan() : void
    {
        $entries = $this->trait->callParseEntries(
            "  MAC Address             Vlan/Vsi/BD         Interface               Oper-Type         Type        \n" .
            "  00:0b:78:66:db:d0       205/--/--           2.5ge1/0/1              forward           dynamic     \n" .
            "877Cedar.POESW#"
        );

        $this->assertSame( 205, $entries[0]->vlan );
    }

    #[Test]
    public function parseEntriesReturnsNullVsiWhenDash() : void
    {
        $entries = $this->trait->callParseEntries(
            "  MAC Address             Vlan/Vsi/BD         Interface               Oper-Type         Type        \n" .
            "  00:0b:78:66:db:d0       1/--/--             10ge1/1/1               forward           dynamic     \n" .
            "877Cedar.POESW#"
        );

        $this->assertNull( $entries[0]->vsi );
    }

    #[Test]
    public function parseEntriesReturnsNullBdWhenDash() : void
    {
        $entries = $this->trait->callParseEntries(
            "  MAC Address             Vlan/Vsi/BD         Interface               Oper-Type         Type        \n" .
            "  00:0b:78:66:db:d0       1/--/--             10ge1/1/1               forward           dynamic     \n" .
            "877Cedar.POESW#"
        );

        $this->assertNull( $entries[0]->bd );
    }

    #[Test]
    public function parseEntriesReturnsCorrectOperType() : void
    {
        $entries = $this->trait->callParseEntries(
            "  MAC Address             Vlan/Vsi/BD         Interface               Oper-Type         Type        \n" .
            "  00:0b:78:66:db:d0       1/--/--             10ge1/1/1               forward           dynamic     \n" .
            "877Cedar.POESW#"
        );

        $this->assertSame( 'forward', $entries[0]->operType );
    }

    #[Test]
    public function parseEntriesReturnsCorrectType() : void
    {
        $entries = $this->trait->callParseEntries(
            "  MAC Address             Vlan/Vsi/BD         Interface               Oper-Type         Type        \n" .
            "  d4:01:c3:02:88:6f       1/--/--             10ge1/1/1               forward           static      \n" .
            "877Cedar.POESW#"
        );

        $this->assertSame( 'static', $entries[0]->type );
    }

    #[Test]
    public function parseEntriesReturnsInstanceOfMacEntry() : void
    {
        $entries = $this->trait->callParseEntries(
            "  MAC Address             Vlan/Vsi/BD         Interface               Oper-Type         Type        \n" .
            "  00:0b:78:66:db:d0       1/--/--             10ge1/1/1               forward           dynamic     \n" .
            "877Cedar.POESW#"
        );

        $this->assertInstanceOf( MacEntry::class, $entries[0] );
    }

    #[Test]
    public function parseEntriesSkipsShortRows() : void
    {
        $entries = $this->trait->callParseEntries(
            "  MAC Address             Vlan/Vsi/BD         Interface               Oper-Type         Type        \n" .
            "  00:0b:78:66:db:d0       1/--/--             10ge1/1/1               forward           dynamic     \n" .
            "  incomplete row\n" .
            "877Cedar.POESW#"
        );

        $this->assertCount( 1, $entries );
    }

    #[Test]
    public function parseEntriesHandlesMixedInterfaces() : void
    {
        $entries = $this->trait->callParseEntries(
            "  MAC Address             Vlan/Vsi/BD         Interface               Oper-Type         Type        \n" .
            "  00:0b:78:66:db:d0       1/--/--             10ge1/1/1               forward           dynamic     \n" .
            "  e4:8d:8c:7e:77:45       205/--/--           2.5ge1/0/1              forward           dynamic     \n" .
            "877Cedar.POESW#"
        );

        $this->assertSame( '10ge1/1/1',  $entries[0]->interface );
        $this->assertSame( '2.5ge1/0/1', $entries[1]->interface );
    }

    #[Test]
    public function parseEntriesHandlesNumericVlan() : void
    {
        $entries = $this->trait->callParseEntries(
            "  MAC Address             Vlan/Vsi/BD         Interface               Oper-Type         Type        \n" .
            "  00:0b:78:66:db:d0       219/--/--           2.5ge1/0/8              forward           dynamic     \n" .
            "877Cedar.POESW#"
        );

        $this->assertSame( 219, $entries[0]->vlan );
    }

    #[Test]
    public function parseEntriesReturnsNullVlanWhenNotNumeric() : void
    {
        $entries = $this->trait->callParseEntries(
            "  MAC Address             Vlan/Vsi/BD         Interface               Oper-Type         Type        \n" .
            "  00:0b:78:66:db:d0       abc/--/--           10ge1/1/1               forward           dynamic     \n" .
            "877Cedar.POESW#"
        );

        $this->assertNull( $entries[0]->vlan );
    }



    /* GET MAC TABLE TESTS
    ----------------------------------------------------------------------------- */

    #[Test]
    public function getMacTableThrowsOnInvalidOutput() : void
    {
        $trait = new class {
            use \Ocolin\Hyconext\Traits\SSH\MacTableTrait;

            public function runCommand( string $command ) : string
            {
                return "not enough parts";
            }
        };

        $this->expectException( RuntimeException::class );
        $this->expectExceptionMessage( 'Error parsing MAC Table.' );
        $trait->getMacTable();
    }

}