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
        'qnames' => array(
            'http://schema.org/name',
            'http://purl.org/dc/terms/title'
        )
    );
    
// kind of material
$cpss[] = (object)array(
        'display_name' => 'Kind of Material',
        'required' => false,
        'qnames' => array(
            'http://rs.tdwg.org/dwc/terms/basisOfRecord',
            'http://rs.tdwg.org/dwc/terms/BasisOfRecord'
        )
    );

// scientific name (current)
$cpss[] = (object)array(
        'display_name' => 'Scientific Name',
        'required' => false,
        'qnames' => array(
            'http://rs.tdwg.org/dwc/terms/scientificName',
            'http://rs.tdwg.org/dwc/terms/ScientificName'
        )
    );

// family
$cpss[] = (object)array(
        'display_name' => 'Family',
        'required' => false,
        'qnames' => array(
            'http://rs.tdwg.org/dwc/terms/family',
            'http://rs.tdwg.org/dwc/terms/Family'
        )
    );
    
// original scientific name
$cpss[] = (object)array(
        'display_name' => 'Original Scientific Name',
        'required' => false,
        'qnames' => array(
            'http://rs.tdwg.org/dwc/terms/originalNameUsage'
        )
    );

// collector name
$cpss[] = (object)array(
        'display_name' => 'Collector Name (recordedBy)',
        'required' => false,
        'qnames' => array(
            'http://schema.org/creator',
            'http://rs.tdwg.org/dwc/terms/recordedBy',
            'http://rs.tdwg.org/dwc/terms/Collector'
        )
    );

// collector number
$cpss[] = (object)array(
        'display_name' => 'Collector Number (recordNumber)',
        'required' => false,
        'qnames' => array(
            'http://rs.tdwg.org/dwc/terms/recordNumber',
            'http://rs.tdwg.org/dwc/terms/CollectorNumber'
         )
    );

// collection date
$cpss[] = (object)array(
        'display_name' => 'Collection Date (eventDate)',
        'required' => false,
        'qnames' => array(
            'https://schema.org/dateCreated',
            'http://rs.tdwg.org/dwc/terms/eventDate',
            'http://rs.tdwg.org/dwc/terms/EarliestDateCollected'
         )
    );



// geo coordinates (lon/lat; WGS84)
$cpss[] = (object)array(
        'display_name' => 'Decimal Longitude (WGS84)',
        'required' => false,
        'qnames' => array(
            array('http://schema.org/locationCreated', 'http://schema.org/geo', 'http://schema.org/longitude'),
            'http://rs.tdwg.org/dwc/terms/decimalLongitude',
            'http://rs.tdwg.org/dwc/terms/DecimalLongitude'
         )
    );

$cpss[] = (object)array(
        'display_name' => 'Decimal Latitude (WGS84)',
        'required' => false,
        'qnames' => array(
            array('http://schema.org/locationCreated', 'http://schema.org/geo', 'http://schema.org/latitude'),
            'http://rs.tdwg.org/dwc/terms/decimalLatitude',
            'http://rs.tdwg.org/dwc/terms/DecimalLatitude'
         )
    );




// iso country
$cpss[] = (object)array(
        'display_name' => '2 Letter ISO Country code',
        'required' => false,
        'note' => 'Client may ignore if not two upper case letters or may display anyway',
        'qnames' => array(
            array('http://schema.org/locationCreated', 'http://schema.org/geo', 'http://schema.org/addressCountry'),
            'http://rs.tdwg.org/dwc/terms/countryCode',
            'http://rs.tdwg.org/dwc/terms/country',
            'http://rs.tdwg.org/dwc/terms/Country'
         )
    );

// source link (URI to institition/collection/owner) 
$cpss[] = (object)array(
        'display_name' => 'Source Link',
        'required' => false,
        'note' => 'Expects a URL to the hosting institutions home page not embedded object.',
        'qnames' => array(
            array('http://schema.org/publisher', 'http://schema.org/url'),
            'http://rs.tdwg.org/dwc/terms/InstitutionCode'
         )
    );
    
// Institution Logo 
$cpss[] = (object)array(
        'display_name' => 'Institution Logo',
        'required' => false,
        'note' => 'Expects a URL to logo of institution.',
        'qnames' => array(
            array('http://schema.org/publisher', 'http://schema.org/logo')
         )
    );
    
// link to "nice" webscaled image
$cpss[] = (object)array(
        'display_name' => 'Image of specimen',
        'required' => false,
        'note' => 'Expects URL to file. Max 1,000x1,000 px. JPEG, PNG, GIF',
        'qnames' => array(
            'http://schema.org/image', // used on over 1,000,000 domains
            'http://rs.tdwg.org/dwc/terms/associatedMedia'
         )
    );

?>