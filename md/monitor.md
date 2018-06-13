# Monitor

It is really important that implementers of CETAF Identifiers keep their systems stable. This can be challenging when IT systems are technically and organisationally complex. We have developed an experimental monitor service to alert implementers to issues with their systems.

## How it works

Implementers provide a simple text file. The first line of the text file is an email address. Subsequent lines each contain a single CETAF Identifier - recommended maximum of 1,000.

Once each hour the monitor randomly picks one of the CETAF Identifiers in the file and calls it for RDF and HTML. It also calls the redirect URIs returned by initial calls.

Once a day the monitor sends an email to the email address on the first line of the file with the results of calls from the last 24 hours.

If all the calls have resulted in 303 redirects and redirected calls to RDF have resulted in a 200 response with RDF that can be parsed to produce one or more triples then the subject line of the email will start "OK: CETAF IDs ". If these conditions aren't met then the subject will start "FAULTS: CETAF IDs ".

The monitor service is only for implementers who are at Level 2 or above and have implemented RDF metadata responses. Currently it does not test the contents of the RDF metadata for Level 3 compliance. 

Admins can write simple email filters to hide "OK" if need be. At this stage providing a failsafe OK email ensures the monitor is running!

All monitor data is kept. In future we may provide a graphical display of implementer performance across the network.

## Setting up monitoring

If you would like to be added to the monitor service please email a text file as described above to Roger Hyam (<r.hyam@rbge.org.uk>)





