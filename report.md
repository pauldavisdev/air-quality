# UFCFR5-15-3: Advanced Topics in Web Development 2 Assignment

- Contents
  * [Source Code](#source-code)
  * [XML Parsing](#xml-parsing)
    + [Stream Parsing](#stream-parsing)
    + [DOM Parsing](#dom-parsing)
  * [Extending the Data Visualisation](#extending-the-data-visualisation)

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

Parsing XML files can be executed in different ways and each of these has their own tradeoffs. Different factors must be considered when deciding which method to use and the solution is problem-specific. I am going to briefly discuss two methods of parsing XML - stream parsing and document object model (DOM) parsing.

### Stream Parsing

Stream-oriented parsing involves parsing an XML file, as it is read at runtime. Essentially, the file is 'walked through', one part at a time, and the data is processed as it is read. After being read, the information is discarded and the program moves onto the next data in the file being parsed.

### DOM Parsing

DOM-oriented parsing works by loading the entire XML file to be parsed into memory. Having the whole document loaded into memory allows for operations to be performed on the whole XML document. Having all of the document's data available to parse at once means that it is more flexible to work with than stream-oriented parsing methods because information can be read and across the whole document at any time. 

Query languages such as XPath can be used to query, select, and manipulate the data from the XML document, whereas stream-oriented parsers would require a more complex, user implementation to perform similar operations.

The main disadvantage of DOM-oriented parsing is that the entire document is required to be loaded into memory at once which can utilise too much system memory or even exceed the system memory. Because of this, DOM-oriented XML parsing is not always a feasible solution. This method of parsing XML would best be used in situations where the system memory is large enough to hold and parse the document.

## Extending the Data Visualisation