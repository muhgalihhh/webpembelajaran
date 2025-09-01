import numpy as np
from typing import Optional, Tuple


class SinglePerceptron:
	def __init__(self, learning_rate: float = 0.1, num_epochs: int = 1000, random_state: Optional[int] = 42, shuffle: bool = True):
		self.learning_rate = learning_rate
		self.num_epochs = num_epochs
		self.random_state = random_state
		self.shuffle = shuffle
		self.weights: Optional[np.ndarray] = None
		self.bias: float = 0.0

	def _prepare(self, X: np.ndarray):
		if self.random_state is not None:
			rng = np.random.default_rng(self.random_state)
		else:
			rng = np.random.default_rng()
		self.weights = rng.normal(0.0, 0.01, size=X.shape[1])
		self.bias = 0.0

	@staticmethod
	def _step(z: np.ndarray) -> np.ndarray:
		return (z >= 0).astype(int)

	def fit(self, X: np.ndarray, y: np.ndarray) -> Tuple[list, list]:
		X = X.astype(float)
		y = y.astype(int)
		self._prepare(X)
		losses = []
		accuracies = []
		indices = np.arange(X.shape[0])

		for epoch in range(self.num_epochs):
			if self.shuffle:
				np.random.shuffle(indices)
				x_epoch = X[indices]
				y_epoch = y[indices]
			else:
				x_epoch, y_epoch = X, y

			epoch_errors = 0
			for xi, yi in zip(x_epoch, y_epoch):
				z = np.dot(xi, self.weights) + self.bias
				pred = 1 if z >= 0 else 0
				update = self.learning_rate * (yi - pred)
				if update != 0:
					self.weights += update * xi
					self.bias += update
					epoch_errors += int(update != 0)

			# simple 0/1 loss proxy: number of misclassifications
			y_pred = self.predict(X)
			losses.append(int(np.sum(y_pred != y)))
			accuracies.append(float(np.mean(y_pred == y)))

		return losses, accuracies

	def predict(self, X: np.ndarray) -> np.ndarray:
		z = X @ self.weights + self.bias
		return self._step(z)

	def score(self, X: np.ndarray, y: np.ndarray) -> float:
		return float(np.mean(self.predict(X) == y))

