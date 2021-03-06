Drupal.behaviors.islandora_herbarium_object_graph = {
  attach: function (context, settings) {
    // only run once:
    if (context == document) {
      var svg = d3.select(".fdg"),
          width = +svg.attr("width"),
          height = +svg.attr("height");
      var radius = 25;
      var color = d3.scaleOrdinal(d3.schemeCategory20);

      var simulation = d3.forceSimulation()
        .force("link", d3.forceLink().id(function (d) {
          return d.id;
        }).distance(250).strength(0.5))
        .force("charge", d3.forceManyBody())
        .force("center", d3.forceCenter(width / 2, height / 2));
        //.force("gravity",gravity(0.2));
      var dataPath = "/" + settings.path.currentPath.replace('fdg', 'fdg_json');
      d3.json(dataPath, function (error, graph) {
        if (error) throw error;

        var link = svg.append("g")
          .attr("class", "links")
          .selectAll("line")
          .data(graph.links)
          .enter().append("line")
          .attr("stroke-width", function (d) {
            return Math.sqrt(d.value);
          });

        var node = svg.append("g")
          .attr("class", "nodes")
          .selectAll("circle")
          .data(graph.nodes)
          .enter().append("circle")
          .attr("r", 12)
          .attr("fill", function (d) {
            return color(d.group);
          })
          .call(d3.drag()
            .on("start", dragstarted)
            .on("drag", dragged)
            .on("end", dragended));

        node.append("title")
          .text(function (d) {
            return d.id;
          });

        var uriElement = svg.append('g')
          .attr('class', 'uri')
          .attr('x', 8)
          .attr('y', '.31em')
          .selectAll('uri')
          .data(graph.nodes)
          .enter().append('a')
          .attr("target", "_blank")
          .attr('xlink:href', function (d) {
            return d.uri
          })
          .append('text')
          .text(function (d) {
            return d.name;
          })
          .attr('font-size', 10)
          .attr('font-weight', 'bolder');


        simulation
          .nodes(graph.nodes)
          .on("tick", ticked);

        simulation.force("link")
          .links(graph.links);


        function ticked() {
          link
            .attr("x1", function (d) {
              return d.source.x;
            })
            .attr("y1", function (d) {
              return d.source.y;
            })
            .attr("x2", function (d) {
              return d.target.x;
            })
            .attr("y2", function (d) {
              return d.target.y;
            });

        node
          .attr("cx", function(d) { return d.x = Math.max(radius, Math.min(width - radius, d.x)); })
          .attr("cy", function(d) { return d.y = Math.max(radius, Math.min(height - radius, d.y)); });

          //textElement
          //  .attr("dx", function(d) {return d.x})
          //.attr("dy", function(d) { return d.y; });

          uriElement
            .attr("dx", function (d) {
              return d.x
            })
            .attr("dy", function (d) {
              return d.y;
            });
        }
                
      });
      
      function gravity(alpha) {
        return function(d) {
            d.y += (d.cy - d.y) * alpha;
            d.x += (d.cx - d.x) * alpha;
        };
      }

      function dragstarted(d) {
        if (!d3.event.active) simulation.alphaTarget(0.3).restart();
        d.fx = d.x;
        d.fy = d.y;
      }

      function dragged(d) {
        d.fx = d3.event.x;
        d.fy = d3.event.y;
      }

      function dragended(d) {
        if (!d3.event.active) simulation.alphaTarget(0);
        d.fx = null;
        d.fy = null;
      }

    }
  }
}
