# Filming in Paris
 
## How it works
- a web page with a search field and a button
- the form triggers a search in the database of film shoots in Paris
- the possible answers are displayed as a list
- if the user clicks on a film location, a map positions it in the city using the GPS coordinates provided

## Resources
The city of Paris provides an API for filming locations on its [open data] site(https://opendata.paris.fr/). This file available in JSON and other formats is updated once a year.

For the map several solutions are available online. We will use Open Street Map through the API of Leaflet, one of the libraries allowing its use.
