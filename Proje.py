import pygame
import numpy as np
import random

pygame.init()
screen = pygame.display.set_mode(...)
clock = pygame.time.Clock()

particles = create_particles(...)
target = build_figure()

def update():
    for p in particles:
        p.x += (p.tx - p.x) * ...
        p.y += (p.ty - p.y) * ...

def draw():
    screen.fill((0, 0, 0))
    for p in particles:
        pygame.draw.circle(
            (int(p.x), int(p.y)...)
        )

while True:
    update(...)
    draw()
    pygame.display.flip()
    clock.tick(60)