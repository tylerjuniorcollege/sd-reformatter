# SoftDocs HTML Formatter

HTML Parser and Reformatter for Forms to be used with the SoftDocs system

## Documentation
### Using the Application.
#### Starting Upload/Submission.
The application's main page has two submission options. 
  1. Uploading an HTML document to be parsed.
  2. URL to an HTML form document to be parsed.

#### Parsing the Document.
After submitting the document, the application will begin to parse the document based on the rules for SoftDocs, and will display the results of the parser.

#### Downloading the Document.



## Versions
### 0.1
Currently the features in v0.1 are:
  * HTML Parsing from HTML Documents and Web Pages (like a Jotform or Google Forms URL)
  * Checks HTML for the following:
    * Cleans all &nbsp; (Non-Breaking Spaces)
    * Checks for Unique Input Names
    * Fixes `readonly>` to `readonly=“readonly”`>
    * Inserts `<!-- -->` for all empty tags.
    * Label Elements match Input Elements
  * Additionally, it allows for downloading any Javascript files and Stylesheets from any remote server.

Known Bugs in Version 0.1
  * When trying to reprocess an online form, by submitting the same online form multiple times regardless of any changes, multiple listings of the javascript and CSS files will appear in the download link section.
  * Submit Buttons might not be removed from HTML files. (Still testing)

### 0.2
Upcoming Features for version 0.2 are:
  * Final HTML Checks
    * Check for Unique Values per input.
    * Full check for removing Submit Button.
  * Inject HTML Wrapper for TJC CSS file.
  * Change remote URLs to local URLs.
  * Outputting rules for the final document.
  * Asset Compression (Compress Javascript and CSS Files to a single file for each)
  * In App HTML Editing/Preview before output
  * Transform.xsl upload and fix (Remove `<!CDATA[ ]>` tag)