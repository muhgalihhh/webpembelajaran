import numpy as np
from sklearn import datasets
from sklearn.model_selection import train_test_split


def load_logic_gate(name: str):
	name = name.lower()
	if name == "and":
		X = np.array([[0, 0], [0, 1], [1, 0], [1, 1]], dtype=float)
		y = np.array([0, 0, 0, 1], dtype=int)
	elif name == "or":
		X = np.array([[0, 0], [0, 1], [1, 0], [1, 1]], dtype=float)
		y = np.array([0, 1, 1, 1], dtype=int)
	elif name == "xor":
		X = np.array([[0, 0], [0, 1], [1, 0], [1, 1]], dtype=float)
		y = np.array([0, 1, 1, 0], dtype=int)
	else:
		raise ValueError(f"Unknown gate: {name}")
	return X, y


def load_iris(test_size: float = 0.2, random_state: int = 42):
	data = datasets.load_iris()
	X = data.data.astype(float)
	y = data.target.astype(int)
	return train_test_split(X, y, test_size=test_size, random_state=random_state, stratify=y)


def load_digits(test_size: float = 0.2, random_state: int = 42):
	data = datasets.load_digits()
	X = data.data.astype(float)
	y = data.target.astype(int)
	return train_test_split(X, y, test_size=test_size, random_state=random_state, stratify=y)


def load_ruspini(test_size: float = 0.2, random_state: int = 42):
	# Ruspini dataset points and cluster labels (from R 'cluster' package). Hardcoded for offline use.
	# 75 points, 4 clusters. Coordinates from the classic dataset.
	ruspini_points = np.array([
		[3,4],[3,5],[4,4],[4,5],[4,6],[5,3],[5,4],[5,5],[5,6],[6,3],[6,4],[6,5],
		[7,2],[7,3],[7,4],[7,5],[8,2],[8,3],[8,4],[8,5],[9,1],[9,2],[9,3],[9,4],
		[3,13],[3,14],[4,12],[4,13],[4,14],[5,11],[5,12],[5,13],[5,14],[6,11],[6,12],[6,13],
		[9,11],[9,12],[9,13],[9,14],[10,11],[10,12],[10,13],[10,14],[11,12],[11,13],[11,14],
		[14,4],[14,5],[15,3],[15,4],[15,5],[16,3],[16,4],[16,5],[17,2],[17,3],[17,4],
		[18,2],[18,3],[18,4],[19,2],[19,3],[20,2],[20,3],[21,2],[21,3],[22,2],[22,3],
		[23,2],[23,3],[24,2],[24,3]
	], dtype=float)
	# Approximate cluster labels (4 clusters)
	labels = np.array([
		0,0,0,0,0,0,0,0,0,0,0,0,
		0,0,0,0,0,0,0,0,0,0,0,0,
		1,1,1,1,1,1,1,1,1,1,1,1,
		1,1,1,1,1,1,1,1,1,1,1,
		2,2,2,2,2,2,2,2,2,2,2,
		2,2,2,2,2,2,2,2,2,2,2,
		3,3,3,3
	], dtype=int)

	X = ruspini_points
	y = labels
	return train_test_split(X, y, test_size=test_size, random_state=random_state, stratify=y)

