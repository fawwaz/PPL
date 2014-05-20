=== Easy Graphs ===
Contributors: technosailor 
Tags: graphs, charts, data visualization
Requires at least: WordPress 3.3
Tested up to: WordPress 3.4
Stable tag: trunk

This plugin allows for simple data visualization in post content. It is Multisite compatible and relies on shortcodes to render the graphs.

== Description ==

Everyone likes data visualizations so I wrote a plugin that will make the quick and secure creation of Pie Charts, Bar Charts and Line Charts easy. The answer is: Easy Graphs.

Easy graphs is very simple to use. It's a shortcode - [easy_graphs]

Not just like that. The shortcode also requires one parameter "data". This parameter is a comma separated list of number values "1,2,3,4,5,6,7".

You can also add a "type" parameter. The "type" can be "line", "bar" or "pie". "bar" is the default.

For a Bar graph, you can optionally pass additional parameters: width, height, and color.

Example: [easy_graphs height="200" type="bar" data="30,70,65"]

By default, width and height are populated from your media embed sizes that are set in your Settings > Media menu inside WordPress. Color should be a hex color code.

For a line graph, the options are similar: color1, color2, height and width.

If you will: [easy_graphs height="200" type="line" data="200,150,175,260"]

In this case, color1 is the "fill" color and color2 is the line color. Both should be hex.

For Pie charts, there are some additional limitations but fewer parameters: color1, color2 and diameter.

Try this: [easy_graphs diameter="150" data="40,60" type="pie"]

Diameter should be an integer represented in pixels. Color1 and color2 are the fill colors of the pie slices. The limitation is the pie chart, at this time, can only take 2 values. I'll work on that.

On the roadmap are other things. Make the pie chart take more values than just 2. Maybe include other graph types. Labels so the data can be more easily understood.

What would you add to this?

== Installation ==

Extract the zip file and just drop the contents in the wp-content/plugins/ directory of your WordPress installation and then activate the Plugin from Plugins page.
== Screenshots ==
1. Rendered charts.

== Frequently Asked Questions ==

= Q: Where can I get support?

= A: Click on that support link on the right and start a post. Please don't email me directly and for the love of all that is good and holy, don't ask me on Twitter. :)
