### Experiments for Drush 14

[This blog post](https://medium.com/@fernando_28520/extend-your-symfony-console-app-with-events-and-attributes-ca8ec6321430) was an inspiration for the approach here.

A Console app which explores moving on from AnnotatedCommand in favor of more native use of Console. It currently implements a single command - `bin/console.php user:information --help`
   1. HookManager replaced by regular EventDispatcher and Listeners
   1. Arguments and Options, including default values, are provided via the configure() method.
   1. Interaction is handled b ytbe command itself in the insteract() method. 
   1. Gone are CommandFactory, Parsing into Commandinfo, etc.
   1. See command and listener discovery in `bin/console`.
   1. This app uses a Symfony container instead of a League container. 

### Todo
- This app currently uses the Symfony Command object directly. Consider extending it instead, as AnnotatedCommand does today.
- Consider using configure() method instead of Arg and Option Attributes
- Deal with extended features like Topics
- Deal with DrushCommands methods like logger(), io(), etc.
- This app uses $services->load() to discover commands and listeners. Listeners must be named with suffixes corresponding to their event. Is OK?
- This app is using the Symfony container to store commands and listeners. Drush moved away from that recently.
- Do we need more Events? The current lifecycle is:
  - ConsoleInitEvent (e.g. alter command definition). This is the only event we created.
  - interact() method (e.g. interactively get argument values)
  - ConsoleCommandEvent (validate)
  - execute() (do work and then format result)
  - ConsoleTerminateEvent
