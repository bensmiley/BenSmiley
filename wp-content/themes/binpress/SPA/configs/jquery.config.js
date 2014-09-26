define(['jquery', 'jqueryvalidate'], function(jQuery) {
  jQuery.validator.setDefaults({
    debug: true,
    success: "valid"
  });
  return jQuery.validator.addMethod("domain", (function(nname) {
    var dname, dot, ext, mai, name, val;
    name = nname.replace('http://', '');
    nname = nname.replace('https://', '');
    mai = nname;
    val = true;
    dot = mai.lastIndexOf(".");
    dname = mai.substring(0, dot);
    ext = mai.substring(dot, mai.length);
    if (dot > 2 && dot < 57) {
      return true;
    }
    return false;
  }), 'Invalid domain name.');
});
