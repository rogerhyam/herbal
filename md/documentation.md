
# Documentation

A robust mechanism to reference specimens held by natural history collections across the internet has been a topic of discussion in the biodiversity informatics community for some time.

In the last few years stable HTTP URIs (URLs or IRIs) have been adopted by a number of major institutions so that
today many millions of specimens are referenceable using this approach.
More institutions are showing an interest in implementing stable URIs for their specimens and there is
interest in adding more functionality to those that are in place.

This is a simple service for testing specimen URIs. It is hoped it will accelerate adoption of URIs
and improve quality/scope of implementations.

Why "herbal"? This service is being run by the Royal Botanic Garden Edinburgh. It 
needed a name and we have the keyboard so get to choose!
But everything here is as applicable to zoological (or geological) specimens as botanical ones.

## Background

The development of use of URIs for specimens is very much a community effort involving
institutions mainly acting under the umbrella of the Information Science and
Technology Commission (ISTC) of the Consortium of European Taxonomic Facilities (CETAF).

One catalyst for adoption of URIs for specimens was this paper:

**Hyam, R.D., Drinkwater, R.E. & Harris, D.J.** *Stable citations for herbarium specimens on the internet: an illustration from a taxonomic revision of Duboscia (Malvaceae)*
Phytotaxa 73: 17–30 (2012). [[PDF](http://www.mapress.com/phytotaxa/content/2012/f/pt00073p030.pdf)]

It gives some of the justification for their use as well as examples in a print publication. The paper was presented at a [CETAF meeting](http://stories.rbge.org.uk/archives/1377).
The presentation slides give a graphical summary [[PDF](http://stories.rbge.org.uk/wp-content/uploads/2013/03/specimen_id_presentation_01.pdf)].
A follow up meeting/workshop under the auspices of CETAF was held where [a series of presentations were given](http://stories.rbge.org.uk/archives/3846).
At this meeting BGBM Berlin, RBGE Kew and MNHN Paris expressed interest in implementation which set the ball rolling for wider adoption.
The most recent discussions were at a [CETAF meeting in Geneva](http://cetafidentifiers.biowikifarm.net/wiki/Geneva_meeting) where this tester site was proposed.

Please refer to these resources for detailed discussion of implementation of HTTP URIs for specimens. Only a summary is given below.

## Levels of Implementation

Conceptually it is easy to divide implementation of HTTP URIs for specimens into
three levels. Institutions can participate by implementing any of these levels.

### Level 1

A simple HTTP URI is used to represent each specimen in the collection, or at least all those that have been databased and are available over the internet.
The word 'simple' here means that the URI does not contain a query string or other information related to implementation of the server such as a .php or .aspx file ending.

When the URI is resolved (e.g. used in the address bar of a web browser) then a useful, human readable resource is returned (i.e. a web page). Level 1 can be thought of as simply having a web page at a well designed address for every specimen.

Institutions undertake to maintain these URIs even if their underlying database and web systems change thus providing stability. 
Other organisations can store and quote the URIs with confidence.

Currently implementers tend to use a catalogue number or barcode number at the end of their URIs.

### Level 2

The response to resolving the URI is an HTTP 303 redirect to another resource.
The resouce redirected to depends on the Accept header given in the request. Typically
it defaults to the web page as in Level 1.
This keeps the implementation transparent to a human user but if a machine sets the
Accept type to 'application/rdf+xml' then the result is RDF metadata about the specimen.

Level 2 provides basic machine readable data and integrates specimen URIs with the semantic web.

### Level 3

Level 2 doesn't specify the content of the RDF data. At Level 3 application specific
data is encoded in the RDF or may be returned in response to other Accept types
passed to the server.

## What this service tests

The tester tests levels 1 and 2 as well as an initial application under level 3.

This initial application is the inclusion of some key fields in the RDF as part of
the [CETAF Specimen Preview Profile (CSPP)](http://cetafidentifiers.biowikifarm.net/wiki/CSPP).

The CSPP has been designed to meet the use case of providing "pretty links" or 
popup boxes in place of displaying the raw HTTP URIs. If a database stores
URIs for specimens in other collections instead of displaying just the URI
to the user it could use CSPP data to display a summary of the specimen 
the user would see if they followed the link. It is analogous to the 
mechanism used by social media sites and search engines when linking to web
pages.



## Status

This service is aimed at developers and will always be in development.
It will improve is through use and the reporting of bugs.
If it doesn't behave as expected or you would like
it to do more please [contact us](/md.php?q=contact).


