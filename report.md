```
Name: Paul Davis  
Student Number: 13026862
```

# UFCFR5-15-3: Advanced Topics in Web Development 2
## Bristol Air Quality Assignment

- Contents
  * [Source Code](#source-code)
  * [XML Parsing](#xml-parsing)
    + [Stream Parsing](#stream-parsing)
    + [DOM Parsing](#dom-parsing)
  * [Data Visualisation](#data-visualisation)
    + [Implementation](#implementation)
    + [Extending the Visualisation](#extending-the-visualisation)

## Source Code
Source code can be found at https://github.com/pauldavisdev/air-quality

```bash
│   .gitignore
│   index.html
│   README.md
│   report.md
│   
├───app
│   │   air_quality.csv
│   │   csv_to_xml.php
│   │   file_reader.php
│   │   get_line_data.php
│   │   get_locations.php
│   │   get_scatter_data.php
│   │   normalise_xml.php
│   │   
│   ├───data_1
│   │       brislington.xml
│   │       fishponds.xml
│   │       newfoundland_way.xml
│   │       parson_st.xml
│   │       rupert_st.xml
│   │       wells_rd.xml
│   │       
│   └───data_2
│           brislington_2.xml
│           fishponds_2.xml
│           newfoundland_way_2.xml
│           parson_st_2.xml
│           rupert_st_2.xml
│           wells_rd_2.xml
│           
├───scripts
│       main.js
│       
└───styles
        style.css
```

## XML Parsing Methods

Parsing XML files can be executed in different ways and each of these has their own tradeoffs. Different factors must be considered when deciding which method to use and the solution is problem-specific. I am going to briefly discuss two methods of parsing XML - [stream parsing](#stream-parsing) and [document object model (DOM) parsing](#dom-parsing).

### Stream Parsing

Stream-oriented parsing involves parsing an XML file, as it is read at runtime. Essentially, the file is 'walked through', one part at a time, and the data is processed as it is read. After being read, the information is discarded and the program moves onto the next data in the file being parsed.

This method has a great performance because it does not load the entire document into memory, so it does not hog so much memory. For large files this is often a better approach than DOM parsing, however it means you are only able to process the file in a linear fashion (although you are able to go back and read the file fromt he start multiple times). This means that you will be required to write your own code to accomplish tasks that would otherwise be completed using query lagnuages, such as XPath, when using DOM parsing.

### DOM Parsing

DOM-oriented parsing works by loading the entire XML file to be parsed into memory. Having the whole document loaded into memory allows for operations to be performed on the whole XML document. Having all of the document's data available to parse at once means that it is more flexible to work with than stream-oriented parsing methods because information can be read and across the whole document at any time. 

Query languages such as XPath can be used to query, select, and manipulate the data from the XML document, whereas stream-oriented parsers would require a more complex, user implementation to perform similar operations.

The main disadvantage of DOM-oriented parsing is that the entire document is required to be loaded into memory at once which can utilise too much system memory or even exceed the system memory. Because of this, DOM-oriented XML parsing is not always a feasible solution. This method of parsing XML would best be used in situations where the system memory is large enough to hold and parse the document.

## Data Visualisation

### Implementation

Firstly, I created a simple project structure. All of the backend code (PHP and data files) is contained in the 'app' directory. I have used [Bootstrap 4.3.1](https://getbootstrap.com/), [jQuery](https://jquery.com/), and [Google Charts](https://developers.google.com/chart/) to display the data.

The two data visualisations are displayed on a single page where a simple javascript event shows the selected graph whilst hiding the other. It does this by changing the graphs CSS display properties. 

There is a clear seperation of concerns, an AJAX call is made by the browser (frontend) and received by the appropriate PHP file (backend). The data from the AJAX call is processed by the PHP file which sends the correct data back to the browser. This data is then displayed using Javascript.

After getting everything up and running and functioning correctly, I went back and refactored some of the PHP files and Javascript.

### Extending the Visualisation

Showing and hiding the graphs in the way that I have is not very extendable and would become hard to maintain and debug if any more graphs were to be added.

If the scope of the assignment was to add more than two graphs, I would have chosen a different approach, such as to use a SPA framework like [Vue.js](https://github.com/vuejs/vue). This would allow me to conditionally render objects on the page and avoid writing a lot of confusing code to accomplish something relatively simple.

Other ideas to improve the functionality would be to add the option to choose from a selection of graphs using the Google Chart API. It would also be good to make it so that the user could only select from dates and times that actually had data.