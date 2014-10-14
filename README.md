# SoftDocs HTML Formatter

HTML Parser and Reformatter for Forms to be used with the SoftDocs system

## Documentation
### Using the Application.
#### Starting Upload/Submission.
The application's main page has two submission options. 
   1) Uploading an HTML document to be parsed.
   2) URL to an HTML form document to be parsed.

#### Parsing the Document.
After submitting the document, the application will begin to parse the document based on the rules for SoftDocs, and will display the results of the parser.

#### Downloading the Document.



## Versions
### 0.1
   * This version is being released minus a few features. To meet requirements, it was decided to move the specific parsers to a different week to release the projects.
      * Currently, the System Includes the ability to:
         * Indicate Unique Names
         * Remove all &nbsp; from the submitted document.
         * Label matches Input element.
         * Fill empty HTML Elements with Comments.
         * Make sure `readonly` is `readonly="readonly"`
         * Remove Submit Button.

      * Additional Features include:
         * Parsing of Javascript and CSS to include in to the system.

      * Features moved to next version (0.2):
         * Specifying a default CSS file to wrap around the html.
         * Unique Values for Radio and Checkboxes.
         * Specifying rules to use when outputting the document.
