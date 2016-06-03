<?php

/*

    from http://cetafidentifiers.biowikifarm.net/wiki/Geneva_meeting

    title (language agnostic), mandatory
    kind of material (preserved specimen, fossil specimen, etc.; will need a controled vocabulary)
    scientific name (current)
    family
    original scientific name
    collector number
    collector name
    link to "nice" webscaled image
    geo coordinates (lon/lat; WGS84)
    iso country
    collection date
    source link (URI to institition/collection/owner) 
    
    see also http://tdwg.github.io/dwc/terms/guides/rdf/index.htm#3_Term_reference

*/

$cpss = array();

// title 
$cpss[] = (object)array(
        'display_name' => 'title',
        'required' => true,
        'resource_expected' => false,
        'qnames' => array(
            'http://purl.org/dc/terms/title'
        )
    );
    
// kind of material
$cpss[] = (object)array(
        'display_name' => 'Kind of Material',
        'required' => false,
        'resource_expected' => true,
        'qnames' => array(
            'http://purl.org/dc/terms/type',
            'http://rs.tdwg.org/dwc/terms/basisOfRecord',
            'http://rs.tdwg.org/dwc/terms/BasisOfRecord'
        )
    );

// scientific name (current)
$cpss[] = (object)array(
        'display_name' => 'Scientific Name (current)',
        'required' => false,
        'resource_expected' => false,
        'qnames' => array(
            'http://rs.tdwg.org/dwc/terms/scientificName',
            'http://rs.tdwg.org/dwc/terms/ScientificName'
        )
    );

// family
$cpss[] = (object)array(
        'display_name' => 'Family',
        'required' => false,
        'resource_expected' => false,
        'qnames' => array(
            'http://rs.tdwg.org/dwc/terms/family',
            'http://rs.tdwg.org/dwc/terms/Family'
        )
    );
    
// original scientific name
$cpss[] = (object)array(
        'display_name' => 'Original Scientific Name',
        'required' => false,
        'resource_expected' => false,
        'qnames' => array(
            'http://rs.tdwg.org/dwc/terms/previousIdentifications',
            'http://rs.tdwg.org/dwc/terms/originalNameUsage',
            'http://rs.tdwg.org/dwc/terms/previousIdentifications'
        )
    );

// collector name
$cpss[] = (object)array(
        'display_name' => 'Collector Name (recordedBy)',
        'required' => false,
        'resource_expected' => false,
        'qnames' => array(
            'http://rs.tdwg.org/dwc/terms/recordedBy',
            'http://rs.tdwg.org/dwc/terms/Collector'
        )
    );

// collector number
$cpss[] = (object)array(
        'display_name' => 'Collector Number (recordNumber)',
        'required' => false,
        'resource_expected' => false,
        'qnames' => array(
            'http://rs.tdwg.org/dwc/terms/fieldNumber',
            'http://rs.tdwg.org/dwc/terms/recordNumber',
            'http://rs.tdwg.org/dwc/terms/CollectorNumber'
         )
    );

// collection date
$cpss[] = (object)array(
        'display_name' => 'Collection Date (eventDate)',
        'required' => false,
        'resource_expected' => false,
        'qnames' => array(
            'http://purl.org/dc/terms/created',
            'http://rs.tdwg.org/dwc/terms/eventDate',
            'http://rs.tdwg.org/dwc/terms/EarliestDateCollected'
         )
    );

// geo coordinates (lon/lat; WGS84)
$cpss[] = (object)array(
        'display_name' => 'Decimal Longitude (WGS84)',
        'required' => false,
        'resource_expected' => false,
        'qnames' => array(
            'http://rs.tdwg.org/dwc/terms/decimalLongitude',
            'http://rs.tdwg.org/dwc/terms/DecimalLongitude'
         )
    );

$cpss[] = (object)array(
        'display_name' => 'Decimal Latitude (WGS84)',
        'required' => false,
        'resource_expected' => false,
        'qnames' => array(
            'http://rs.tdwg.org/dwc/terms/decimalLatitude',
            'http://rs.tdwg.org/dwc/terms/DecimalLatitude'
         )
    );

// iso country
$cpss[] = (object)array(
        'display_name' => '2 Letter ISO Country code',
        'required' => false,
        'resource_expected' => false,
        'note' => 'Client may ignore if not two upper case letters or may display anyway',
        'qnames' => array(
            'http://rs.tdwg.org/dwc/terms/countryCode',
            'http://rs.tdwg.org/dwc/terms/country',
            'http://rs.tdwg.org/dwc/terms/Country'
         )
    );

// source link (URI to institition/collection/owner) 
$cpss[] = (object)array(
        'display_name' => 'Source Link',
        'required' => false,
        'resource_expected' => true,
        'note' => 'Expects a URL to the hosting institutions home page not embedded object.',
        'qnames' => array(
            'http://purl.org/dc/terms/publisher',
            'http://rs.tdwg.org/dwc/terms/InstitutionCode'
         )
    );
    
// link to "nice" webscaled image
$cpss[] = (object)array(
        'display_name' => 'Image of specimen',
        'required' => false,
        'resource_expected' => true,
        'note' => 'Expects URL to file. Max 1,000x1,000 px. JPEG, PNG, GIF',
        'qnames' => array(
            'http://rs.tdwg.org/dwc/terms/associatedMedia'
         )
    );

?>