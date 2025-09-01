import os
import numpy as np
from typing import Tuple
from sklearn.preprocessing import StandardScaler
from sklearn.model_selection import train_test_split

from datasets import load_logic_gate, load_iris, load_digits, load_ruspini
from perceptron import SinglePerceptron
from plot_utils import plot_decision_boundary_2d, plot_curves


OUTPUT_DIR = "/workspace/outputs/single"
FIG_DIR = "/workspace/figures/single"
os.makedirs(OUTPUT_DIR, exist_ok=True)
os.makedirs(FIG_DIR, exist_ok=True)


def run_gate(gate_name: str, lr: float = 0.1, epochs: int = 50):
	X, y = load_logic_gate(gate_name)
	model = SinglePerceptron(learning_rate=lr, num_epochs=epochs, random_state=0)
	losses, accs = model.fit(X, y)
	score = model.score(X, y)
	plot_decision_boundary_2d(model, X, y, f"Single Perceptron - {gate_name.upper()} (acc={score:.2f})",
								 save_path=f"{FIG_DIR}/{gate_name}_decision.png")
	plot_curves(losses, accs, f"Training Curves - {gate_name.upper()}", save_path=f"{FIG_DIR}/{gate_name}_curves.png")
	return score


def preprocess_scale_split(X: np.ndarray, y: np.ndarray, test_size: float = 0.2, random_state: int = 42):
	scaler = StandardScaler()
	X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=test_size, random_state=random_state, stratify=y)
	X_train = scaler.fit_transform(X_train)
	X_test = scaler.transform(X_test)
	return X_train, X_test, y_train, y_test, scaler


def run_dataset(name: str, loader_fn, lr: float = 0.01, epochs: int = 50):
	X_train, X_test, y_train, y_test = loader_fn()
	# scale features for stability
	scaler = StandardScaler()
	X_train = scaler.fit_transform(X_train)
	X_test = scaler.transform(X_test)
	# For single perceptron, we can only do binary classification directly.
	# We will do one-vs-rest and report macro accuracy.
	classes = np.unique(y_train)
	per_class_scores = []
	for cls in classes:
		train_y_bin = (y_train == cls).astype(int)
		test_y_bin = (y_test == cls).astype(int)
		model = SinglePerceptron(learning_rate=lr, num_epochs=epochs, random_state=0)
		losses, accs = model.fit(X_train, train_y_bin)
		acc = model.score(X_test, test_y_bin)
		per_class_scores.append(acc)
		if X_train.shape[1] == 2:
			plot_decision_boundary_2d(model, X_train, train_y_bin, f"Single Perceptron - {name} class {cls}", save_path=f"{FIG_DIR}/{name}_class{cls}_decision.png")
		plot_curves(losses, accs, f"Training Curves - {name} class {cls}", save_path=f"{FIG_DIR}/{name}_class{cls}_curves.png")
	return float(np.mean(per_class_scores))


def main():
	results = {}
	# Logic gates
	for gate in ["and", "or", "xor"]:
		results[f"gate_{gate}"] = run_gate(gate, lr=0.2, epochs=50)
	# Datasets
	results["iris"] = run_dataset("iris", load_iris, lr=0.01, epochs=100)
	results["digits"] = run_dataset("digits", load_digits, lr=0.01, epochs=100)
	results["ruspini"] = run_dataset("ruspini", load_ruspini, lr=0.05, epochs=100)
	for k, v in results.items():
		print(f"SinglePerceptron {k}: accuracy={v:.3f}")


if __name__ == "__main__":
	main()

