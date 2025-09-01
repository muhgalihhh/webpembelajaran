import numpy as np
import matplotlib.pyplot as plt
from typing import Optional, Tuple


def plot_decision_boundary_2d(model, X: np.ndarray, y: np.ndarray, title: str, save_path: Optional[str] = None):
	if X.shape[1] != 2:
		return
	min_x1, max_x1 = X[:, 0].min() - 0.5, X[:, 0].max() + 0.5
	min_x2, max_x2 = X[:, 1].min() - 0.5, X[:, 1].max() + 0.5
	x1_grid, x2_grid = np.meshgrid(
		np.linspace(min_x1, max_x1, 200),
		np.linspace(min_x2, max_x2, 200),
	)
	grid = np.c_[x1_grid.ravel(), x2_grid.ravel()]
	Z = model.predict(grid).reshape(x1_grid.shape)
	plt.figure(figsize=(5, 4))
	plt.contourf(x1_grid, x2_grid, Z, alpha=0.3, cmap="coolwarm")
	sc = plt.scatter(X[:, 0], X[:, 1], c=y, cmap="coolwarm", edgecolor="k")
	plt.title(title)
	plt.xlabel("x1")
	plt.ylabel("x2")
	plt.tight_layout()
	if save_path:
		plt.savefig(save_path, dpi=200)
	plt.close()


def plot_curves(losses, accuracies, title: str, save_path: Optional[str] = None):
	fig, ax = plt.subplots(1, 2, figsize=(8, 3))
	ax[0].plot(losses, label="errors")
	ax[0].set_title("Training errors")
	ax[0].set_xlabel("epoch")
	ax[0].set_ylabel("count")
	ax[1].plot(accuracies, label="accuracy")
	ax[1].set_title("Training accuracy")
	ax[1].set_xlabel("epoch")
	for a in ax:
		a.grid(True, alpha=0.3)
		for spine in a.spines.values():
			spine.set_alpha(0.5)
	plt.suptitle(title)
	plt.tight_layout()
	if save_path:
		plt.savefig(save_path, dpi=200)
	plt.close()

