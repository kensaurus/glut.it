from random import randint
from math import log

class KargerMinCut():

    # 0: Initialize graph
    def __init__(self, graph_file):

        # 0.1: Load graph file
        self.graph = {}
        self.total_edges = 0
        self.vertex_count = 0
        with open('C:\\Users\\governor\\Dropbox\\Coursera\\Algorithms\\kargerMinCut.txt', "r") as file:
            for line in file:
                numbers = [int(x) for x in line.split('\t') if x!='\n']
                vertex = numbers[0]
                vertex_edges = numbers[1:]
                self.graph[vertex] = vertex_edges
                self.total_edges+=len(vertex_edges)
                self.vertex_count+=1            
        self.supervertices = {}
        for key in self.graph:
            self.supervertices[key] = [key]

    # 1: Find the minimum cut
    def find_min_cut(self):
        min_cut = 0
        while len(self.graph)>2:
            # 1.1: Pick a random edge
            v1, v2 = self.pick_random_edge()
            self.total_edges -= len(self.graph[v1])
            self.total_edges -= len(self.graph[v2])
            # 1.2: Merge the edges
            self.graph[v1].extend(self.graph[v2])
            # 1.3: Update all references to v2 to point to v1
            for vertex in self.graph[v2]:
                self.graph[vertex].remove(v2)
                self.graph[vertex].append(v1)
            # 1.4: Remove self loops
            self.graph[v1] = [x for x in self.graph[v1] if x != v1]
            # 1.5: Update total edges
            self.total_edges += len(self.graph[v1])
            self.graph.pop(v2)
            # 1.6: Update cut groupings
            self.supervertices[v1].extend(self.supervertices.pop(v2))
        # 1.7: Calc min cut
        for edges in self.graph.values():
            min_cut = len(edges)
        # 1.8: Return min cut and the two supervertices
        return min_cut, self.supervertices      

    # 2: Pick a truly random edge:
    def pick_random_edge(self):
        rand_edge = randint(0, self.total_edges-1)
        for vertex, vertex_edges in self.graph.items():
            if len(vertex_edges) < rand_edge:
                rand_edge -= len(vertex_edges)
            else:
                from_vertex = vertex
                to_vertex = vertex_edges[rand_edge-1]
                return from_vertex, to_vertex

    # H.1: Helpful young man who prints our graph
    def print_graph(self):
        for key in self.graph:
            print("{}: {}".format(key, self.graph[key]))

graph = KargerMinCut('kargerMinCut.txt')

def full_karger(iterations):
    graph = KargerMinCut('kargerMinCut.txt')
    out = graph.find_min_cut()
    min_cut = out[0]
    supervertices = out[1]
    for i in range(iterations):
        graph = KargerMinCut('kargerMinCut.txt')
        out = graph.find_min_cut()
        if out[0] < min_cut:
            min_cut = out[0]
            supervertices = out[1]
    return min_cut, supervertices

out = full_karger(100)
print("min_cut: {}\nsupervertices: {}".format(out[0],out[1]))
